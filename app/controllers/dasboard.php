<?php 

       public function getTrainingMultinomial(){
        // return View::make('skripsi.hasil-training');
         $stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();//load library stemmer sastrawi
         $stemmer  = $stemmerFactory->createStemmer();//load library stemmer sastrawi
        //cara lambat
         $jumlah_spam = Datatraining::where('kelas','=',2) //menghitung tweet spam
                ->count('id');
         $jumlah_valid = Datatraining::where('kelas','=',1) //menghitung tweet valid
                ->count('id'); 
         $jumlah_tweet = Datatraining::count('id');//menghitung jumlah tweet    

         $data_valids = Datatraining::where('kelas','=',1)->paginate(6); //meretrieve tweet valid
         $data_spams = Datatraining::where('kelas','=',2)->paginate(6); //meretrieve tweet spam
         $data_tweets = Datatraining::paginate(6); //meretrieve seluruh tweet
         
         //membuat array term valid dan spam
         $token_valid = $this->maketoken($data_valids);
         $token_spam = $this->maketoken($data_spams);

         $jumlah_token_valid = count($token_valid);
         $jumlah_token_spam = count($token_spam);

         
        

         #proses menghitung jumlah term kelas valid
        
         $term_valid = array(0);
         foreach ($data_valids as $row) {
             $terms_valid = explode(" ", strtolower($row->tweet)); //mengubah karakter menjadi kecil dan di explode
             $jumlah = count($terms_valid); 
            
             for ($j=0; $j < $jumlah ; $j++) { 
                array_push($term_valid, $stemmer->stem($terms_valid[$j]));//memasukkan term ke dalam daftar token valid
             }
         }
         //menghapus term spasi
         $term_valid = $this->merapikan_term($term_valid);
         #menghitung jumlah term kelas valid , spasi ndak dihitung
         $jumlah_term_valid = count($term_valid);
         

          #proses menghitung jumlah term kelas spam
         $term_spam = array(0);
         foreach ($data_spams as $row) {
             $terms_spam = explode(" ", strtolower($row->tweet)); //mengubah karakter menjadi kecil dan di explode
             $jumlah = count($terms_spam); 
             
             for ($j=0; $j < $jumlah ; $j++) { 
                array_push($term_spam, $stemmer->stem($terms_spam[$j]));//memasukkan term ke dalam daftar term spam
             }
         }

         //menghapus term spasi
         $term_spam = $this->merapikan_term($term_spam);
         #menghitung jumlah term kelas spam , spasi ndak dihitung
         $jumlah_term_spam = count($term_spam);
         

         #memanggil token hasil praproses, selajutnya token hasil seleksi, 
         $token = $this->praproses();
         $jumlah_token = count($token);
         $hasil_training = array();

         
         for ($i=0; $i < $jumlah_token; $i++) { 
              $kemunculan_di_valid = 0;
              $kemunculan_di_spam = 0;

              for ($j=0; $j < $jumlah_term_valid ; $j++) { 
                  if (strpos($stemmer->stem($term_valid[$j]), $token[$i]) !== false) {
                     $kemunculan_di_valid = $kemunculan_di_valid + 1;//menghitung kemunculan term di kelas valid
                 }
              }
              
              for ($j=0; $j < $jumlah_term_spam ; $j++) { 
                  if (strpos($stemmer->stem($term_spam[$j]), $token[$i]) !== false) {
                     $kemunculan_di_spam = $kemunculan_di_spam + 1;//menghitung kemunculan term di kelas spam
                 }
              }

              //menghitung probabilitas term terhadap kelas valid
              $prob_term_valid = ($kemunculan_di_valid + 1)/($jumlah_term_valid + $jumlah_token);

              //menghitung probabilitas term terhadap kelas spam
              $prob_term_spam = ($kemunculan_di_spam + 1)/($jumlah_term_spam + $jumlah_token);
              $hasil_training[$i]['term']=$token[$i];
              $hasil_training[$i]['kemunculan']=$kemunculan_di_valid;
              $hasil_training[$i]['kemunculan_spam']=$kemunculan_di_spam;
              $hasil_training[$i]['prob_valid']=round($prob_term_valid,3);
              $hasil_training[$i]['prob_spam']=round($prob_term_spam,3);
         }


         

         //memanggil token hasil praproses
         // $token = $this->praproses();
         return View::make('training.multinomial')->with('hasil_training',$hasil_training)
         ->with('jumlah_term_valid',$jumlah_term_valid)
         ->with('jumlah_term_spam',$jumlah_term_spam)
         ->with('jumlah_token',$jumlah_token); 

       }