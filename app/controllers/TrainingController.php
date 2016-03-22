<?php

class TrainingController extends BaseController {

    #proses pelatihan data training bernoulli biasa
    public function getTrainingBernoulli(){
         $stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();//load library stemmer sastrawi
         $stemmer  = $stemmerFactory->createStemmer();//load library stemmer sastrawi
         
         $data_tweets = Datatraining::get(); //meretrieve seluruh tweet
         $tweets_valid = Datatraining::where('kelas','=',1)->get();
         $tweets_spam = Datatraining::where('kelas','=',2)->get();

         $jumlah_tweet_valid = Datatraining::where('kelas','=',1)->count('id');
         $jumlah_tweet_spam = Datatraining::where('kelas','=',2)->count('id');
         
         //load token dari tabel praproses
         $fitur_praproses = FiturPraproses::get();
         $i=0;
         foreach ($fitur_praproses as $row) {
             $token[$i] = $row->term;
             $i++;
         }
        
         ///menghitung jumlah token
         $jumlah_token = count($token);
         $hasil_training = array();

         $tweet_valid_praproses = array();
         $k = 0;
         foreach ($tweets_valid as $row) {
                  //praproses tweet
                  $tweet = strtolower($row->tweet);
                  $tweet = $this->removeUsernameHashtag($tweet);
                  $tweet = $this->hapusURL($tweet);
                  $tweet = $this->fungsiHapussimbol($tweet);
                  $tweet = $stemmer->stem($tweet);
                  $tweet_valid_praproses[$k] = $tweet;
                  $k++;
         }
         $k = 0;
         foreach ($tweets_spam as $row) {
                  //praproses tweet
                  $tweet = strtolower($row->tweet);
                  $tweet = $this->removeUsernameHashtag($tweet);
                  $tweet = $this->hapusURL($tweet);
                  $tweet = $this->fungsiHapussimbol($tweet);
                  $tweet = $stemmer->stem($tweet);
                  $tweet_spam_praproses[$k] = $tweet;
                  $k++;
         }
        

         


         for ($i=0; $i < $jumlah_token; $i++) { 
              $happened_valid = 0;
              $happened_spam = 0;

              $a=0;
              for ($a=0; $a < $jumlah_tweet_valid ; $a++) { 
                 $cek_kemunculan = "/\b$token[$i]\b/";
                  if(preg_match($cek_kemunculan, $tweet_valid_praproses[$a])) {
                    //jika iya jumlah kejadian happened_valid ditambah 1
                    $happened_valid = $happened_valid+1;
                  }
              }

              $b=0;
              for ($b=0; $b < $jumlah_tweet_valid ; $b++) { 
                 $cek_kemunculan = "/\b$token[$i]\b/";
                  if(preg_match($cek_kemunculan, $tweet_spam_praproses[$b])) {
                    //jika iya jumlah kejadian happened_valid ditambah 1
                    $happened_spam = $happened_spam+1;
                  }
              }

              // foreach ($tweets_valid as $row) {
              //     //praproses tweet
              //     $tweet = strtolower($row->tweet);
              //     $tweet = $this->removeUsernameHashtag($tweet);
              //     $tweet = $this->hapusURL($tweet);
              //     $tweet = $this->fungsiHapussimbol($tweet);
              //     $tweet = $stemmer->stem($tweet);

              //     //cek apakah tweet valid mengandung token i tidak
              //     $cek_kemunculan = "/\b$token[$i]\b/";
              //     if(preg_match($cek_kemunculan, $tweet)) {
              //       //jika iya jumlah kejadian happened_valid ditambah 1
              //       $happened_valid = $happened_valid+1;
              //     }
                 
              // }

              // foreach ($tweets_spam as $row) {

              //     //praproses tweet
              //     $tweet = strtolower($row->tweet);
              //     $tweet = $this->removeUsernameHashtag($tweet);
              //     $tweet = $this->hapusURL($tweet);
              //     $tweet = $this->fungsiHapussimbol($tweet);
              //     $tweet = $stemmer->stem($tweet);
              //     //cek apakah tweet spam mengandung token i tidak
              //     $cek_kemunculan = "/\b$token[$i]\b/";
              //     if(preg_match($cek_kemunculan, $tweet)) {
              //       //jika iya jumlah kejadian happened_valid ditambah 1
              //       $happened_spam = $happened_spam+1;
              //     }
                  
              // }
            
              
              //menghitung probabilitas term terhadap kelas valid
              $prob_term_valid = ($happened_valid + 1)/($jumlah_tweet_valid + 2);

              //menghitung probabilitas term terhadap kelas spam
              $prob_term_spam = ($happened_spam + 1)/($jumlah_tweet_spam + 2);
              
              //memasukkan ke array hasil_training
              $hasil_training[$i]['term']=$token[$i];
              $hasil_training[$i]['happened_valid']=$happened_valid;
              $hasil_training[$i]['happened_spam']=$happened_spam;
              $hasil_training[$i]['prob_valid']=round($prob_term_valid,10);
              $hasil_training[$i]['prob_spam']=round($prob_term_spam,10);

              //menyimpan hasil training ke database;
              $index = $i+1;
              // $probabilitas_praproses = FiturPraproses::find($index);
              // $probabilitas_praproses->prob_valid_b = round($prob_term_valid,10);
              // $probabilitas_praproses->prob_spam_b = round($prob_term_spam,10);
              // $probabilitas_praproses->save();

         }

         // return dd($hasil_training);

         $k = 0;
         foreach ($tweets_valid as $row) {
                  //praproses tweet
                  $tweet = strtolower($row->tweet);
                  $tweet = $this->removeUsernameHashtag($tweet);
                  $tweet = $this->hapusURL($tweet);
                  $tweet = $this->fungsiHapussimbol($tweet);
                  $tweet = $stemmer->stem($tweet);
                  $tweet_valid_praproses[$k] = $tweet;
                  $k++;
         }
         $k = 0;
         foreach ($tweets_spam as $row) {
                  //praproses tweet
                  $tweet = strtolower($row->tweet);
                  $tweet = $this->removeUsernameHashtag($tweet);
                  $tweet = $this->hapusURL($tweet);
                  $tweet = $this->fungsiHapussimbol($tweet);
                  $tweet = $stemmer->stem($tweet);
                  $tweet_spam_praproses[$k] = $tweet;
                  $k++;
         }
         
         //load token dari tabel praproses
         $fitur_seleksi = FiturSeleksi::get();
         $k=0;
         foreach ($fitur_seleksi as $row) {
             $token_seleksi[$k] = $row->term;
             $k++;
         }
        
         ///menghitung jumlah token hasil seleksi
         $jumlah_token = count($token_seleksi);
         $hasil_training_seleksi = array();

         
         //menghitung probabilitas token hasil seleksi menggunakan metode bernoulli
         for ($i=0; $i < $jumlah_token; $i++) { 
              $happened_valid = 0;
              $happened_spam = 0;

              $a=0;
              for ($a=0; $a < $jumlah_tweet_valid ; $a++) { 
                 $cek_kemunculan = "/\b$token_seleksi[$i]\b/";
                  if(preg_match($cek_kemunculan, $tweet_valid_praproses[$a])) {
                    //jika iya jumlah kejadian happened_valid ditambah 1
                    $happened_valid = $happened_valid+1;
                  }
              }

              $b=0;
              for ($b=0; $b < $jumlah_tweet_valid ; $b++) { 
                 $cek_kemunculan = "/\b$token_seleksi[$i]\b/";
                  if(preg_match($cek_kemunculan, $tweet_spam_praproses[$b])) {
                    //jika iya jumlah kejadian happened_valid ditambah 1
                    $happened_spam = $happened_spam+1;
                  }
              }
             
              // foreach ($tweets_valid as $row) {
              //     //praproses tweet
              //     $tweet = strtolower($row->tweet);
              //     $tweet = $this->removeUsernameHashtag($tweet);
              //     $tweet = $this->hapusURL($tweet);
              //     $tweet = $this->fungsiHapussimbol($tweet);
              //     $tweet = $stemmer->stem($tweet);

              //     //cek apakah tweet valid mengandung token i tidak
              //     $cek_kemunculan = "/\b$token_seleksi[$i]\b/";
              //     if(preg_match($cek_kemunculan, $tweet)) {
              //       //jika iya jumlah kejadian happened_valid ditambah 1
              //       $happened_valid = $happened_valid+1;
              //     }
                 
              // }

              // foreach ($tweets_spam as $row) {

              //     //praproses tweet
              //     $tweet = strtolower($row->tweet);
              //     $tweet = $this->removeUsernameHashtag($tweet);
              //     $tweet = $this->hapusURL($tweet);
              //     $tweet = $this->fungsiHapussimbol($tweet);
              //     $tweet = $stemmer->stem($tweet);
              //     //cek apakah tweet spam mengandung token i tidak
              //     $cek_kemunculan = "/\b$token_seleksi[$i]\b/";
              //     if(preg_match($cek_kemunculan, $tweet)) {
              //       //jika iya jumlah kejadian happened_valid ditambah 1
              //       $happened_spam = $happened_spam+1;
              //     }
                  
              // }
            
              
              //menghitung probabilitas term terhadap kelas valid
              $prob_term_valid = ($happened_valid + 1)/($jumlah_tweet_valid + 2);

              //menghitung probabilitas term terhadap kelas spam
              $prob_term_spam = ($happened_spam + 1)/($jumlah_tweet_spam + 2);
              
              //memasukkan ke array hasil_training
              $hasil_training_seleksi[$i]['term']=$token_seleksi[$i];
              $hasil_training_seleksi[$i]['happened_valid']=$happened_valid;
              $hasil_training_seleksi[$i]['happened_spam']=$happened_spam;
              $hasil_training_seleksi[$i]['prob_valid']=round($prob_term_valid,10);
              $hasil_training_seleksi[$i]['prob_spam']=round($prob_term_spam,10);

              //menyimpan hasil training ke database;
              $index = $i+1;
              // $probabilitas_praproses = FiturSeleksi::find($index);
              // $probabilitas_praproses->prob_valid_b = round($prob_term_valid,10);
              // $probabilitas_praproses->prob_spam_b = round($prob_term_spam,10);
              // $probabilitas_praproses->save();

         }

        


         //kembali ke view
         return View::make('training.bernoulli')
            ->with('hasil_training',$hasil_training)
            ->with('hasil_training_seleksi',$hasil_training_seleksi)
            ->with('jumlah_tweet_valid',$jumlah_tweet_valid)
            ->with('jumlah_tweet_spam',$jumlah_tweet_spam); 
    }


    public function HasilBernoulli(){
        $fitur_praproses = FiturPraproses::get();
        $i = 0;
        $token_praproses =array();

        foreach ($fitur_praproses as $row) {
            $token_praproses[$i]['fitur']=$row->term;
            $token_praproses[$i]['prob_valid_b']=$row->prob_valid_b;
            $token_praproses[$i]['prob_spam_b']=$row->prob_spam_b;
            $i++;
        }

        $fitur_seleksi = FiturSeleksi::get();
        $token_seleksi =array();

        $i=0;
        foreach ($fitur_seleksi as $row) {
            $token_seleksi[$i]['fitur']=$row->term;
            $token_seleksi[$i]['prob_valid_b']=$row->prob_valid_b;
            $token_seleksi[$i]['prob_spam_b']=$row->prob_spam_b;
            $i++;
        }

        return View::make('training.hasil_bernoulli')->with('token_seleksi',$token_seleksi)->with('token_praproses',$token_praproses);

    }

    public function HasilTraining(){
        $fitur_praproses = FiturPraproses::get();
        $i = 0;
        $token_praproses =array();

        foreach ($fitur_praproses as $row) {
            $token_praproses[$i]['fitur']=$row->term;
            $token_praproses[$i]['prob_valid_b']=$row->prob_valid_b;
            $token_praproses[$i]['prob_spam_b']=$row->prob_spam_b;
            $token_praproses[$i]['prob_valid_m']=$row->prob_valid_m;
            $token_praproses[$i]['prob_spam_m']=$row->prob_spam_m;
            $i++;
        }
        //menyimpan ke file json
        file_put_contents("fiturpraproses.json",json_encode($token_praproses));

        $fitur_seleksi = FiturSeleksi::get();
        $token_seleksi =array();

        $i=0;
        foreach ($fitur_seleksi as $row) {
            $token_seleksi[$i]['fitur']=$row->term;
            $token_seleksi[$i]['prob_valid_b']=$row->prob_valid_b;
            $token_seleksi[$i]['prob_spam_b']=$row->prob_spam_b;
            $token_seleksi[$i]['prob_valid_m']=$row->prob_valid_m;
            $token_seleksi[$i]['prob_spam_m']=$row->prob_spam_m;
            $i++;
        }

        //menyimpan ke file json
        file_put_contents("fiturseleksi.json",json_encode($token_seleksi));
        return View::make('training.Hasiltraining')->with('token_seleksi',$token_seleksi)->with('token_praproses',$token_praproses);

    }


    public function HasilMultinomial(){
        $fitur_praproses = FiturPraproses::get();
        $i = 0;
        $token_praproses =array();

        foreach ($fitur_praproses as $row) {
            $token_praproses[$i]['fitur']=$row->term;
            $token_praproses[$i]['prob_valid_m']=$row->prob_valid_m;
            $token_praproses[$i]['prob_spam_m']=$row->prob_spam_m;
            $i++;
        }

        $fitur_seleksi = FiturSeleksi::get();
        $token_seleksi =array();

        $i=0;
        foreach ($fitur_seleksi as $row) {
            $token_seleksi[$i]['fitur']=$row->term;
            $token_seleksi[$i]['prob_valid_m']=$row->prob_valid_m;
            $token_seleksi[$i]['prob_spam_m']=$row->prob_spam_m;
            $i++;
        }

        return View::make('training.hasil_multinomial')->with('token_seleksi',$token_seleksi)->with('token_praproses',$token_praproses);

    }

	 #proses pelatihan data training multinomial biasa
    public function getTrainingMultinomial(){


        
         $stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();//load library stemmer sastrawi
         $stemmer  = $stemmerFactory->createStemmer();//load library stemmer sastrawi
        
         $jumlah_spam = Datatraining::where('kelas','=',2) //menghitung tweet spam
                ->count('id');
         $jumlah_valid = Datatraining::where('kelas','=',1) //menghitung tweet valid
                ->count('id'); 
         $jumlah_tweet = Datatraining::count('id');//menghitung jumlah tweet    

         $data_valids = Datatraining::where('kelas','=',1)->get(); //meretrieve tweet valid
         $data_spams = Datatraining::where('kelas','=',2)->get(); //meretrieve tweet spam
         $data_tweets = Datatraining::get(); //meretrieve seluruh tweet
         
         //membuat  valid dan spam
         $token_valid = $this->maketoken($data_valids);
         
         // app('App\Http\Controllers\SkripsiContoller')->maketoken();
         $token_spam = $this->maketoken($data_spams);

         
         $jumlah_token_valid = count($token_valid);
         $jumlah_token_spam = count($token_spam);

        
         

         #proses menghitung jumlah term kelas valid
         
         $term_valid = array(0);
         foreach ($data_valids as $row) {
             $terms_valid = explode(" ", strtolower($row->tweet)); //mengubah karakter menjadi kecil dan di explode
             $jumlah = count($terms_valid); 
            
             for ($j=0; $j < $jumlah ; $j++) { 
                $str = $this->removeUsernameHashtag($terms_valid[$j]);//panggil fungsi remove username dan hashtag
                $str = $this->hapusURL($str);//menghapus URL
                $str = $this->fungsiHapussimbol($str);//hapus simbol dan angka
                $str = $this->removeCommonWords($str);
                $str = $stemmer->stem($str);
                $terms = explode(" ",$str);

                $jumlah_term = count($terms);
                if ($jumlah_term > 1) {
                for ($k=0; $k < $jumlah_term ; $k++) { 
                    //memasukkan term term ke dalam daftar term valid
                    array_push($term_valid,$terms[$k]);
                    }
                }else{
                    //memasukkan term ke dalam daftar term valid
                    array_push($term_valid,$terms[0]);
                }  
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
                $str = $this->removeUsernameHashtag($terms_spam[$j]);//panggil fungsi remove username dan hashtag
                $str = $this->hapusURL($str);//menghapus URL
                $str = $this->fungsiHapussimbol($str);//hapus simbol dan angka
                $str = $this->removeCommonWords($str);
                $str = $stemmer->stem($str);
                $terms = explode(" ",$str);

                $jumlah_term = count($terms);
                if ($jumlah_term > 1) {
                for ($k=0; $k < $jumlah_term ; $k++) { 
                    array_push($term_spam,$terms[$k]);//memasukkan term term ke dalam daftar term valid
                    }
                }else{
                    array_push($term_spam,$terms[0]);//memasukkan term ke dalam daftar term valid
                }  
             }
         }

         //menghapus term spasi
         $term_spam = $this->merapikan_term($term_spam);
         #menghitung jumlah term kelas spam , spasi ndak dihitung
         $jumlah_term_spam = count($term_spam);
         

         #memanggil token hasil praproses dan selajutnya token hasil seleksi

         //A. Memanggil token hasil praproses via file txt
         // $token = unserialize(file_get_contents('token2.txt'));
         
         // B. Memanggil token hasil praproses via database
         $fitur_praproses = FiturPraproses::get();
         $i = 0;
         foreach ($fitur_praproses as $row) {
            $token[$i] = $row->term;
            $i++;
         }

         //menghitung jumlah token praproses
         $jumlah_token = count($token);

         $hasil_training = array();
         
         
         for ($i=0; $i < $jumlah_token; $i++) { 
              $kemunculan_di_valid = 0;
              $kemunculan_di_spam = 0;

              for ($j=0; $j < $jumlah_term_valid ; $j++) { 
                  if ($term_valid[$j] == $token[$i]) {
                     $kemunculan_di_valid = $kemunculan_di_valid + 1;//menghitung kemunculan term di kelas valid
                  }
              }
              
              for ($j=0; $j < $jumlah_term_spam ; $j++) { 
                  if ($term_spam[$j] == $token[$i]) {
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
              $hasil_training[$i]['prob_valid']=round($prob_term_valid,10);
              $hasil_training[$i]['prob_spam']=round($prob_term_spam,10);

              //karena ID nya int sedangkan index di hasil training pake nya array jadi dimulai dari 0, maka perlu ditambah 1
              $index = $i+1;
              #menyimpan hasil training ke database
              // $probabilitas_praproses = FiturPraproses::find($index);
              // $probabilitas_praproses->prob_valid_m = round($prob_term_valid,10);
              // $probabilitas_praproses->prob_spam_m = round($prob_term_spam,10);
              // $probabilitas_praproses->save();
         }

         #memanggil token hasil praproses dan selajutnya token hasil seleksi, 
         //A. via file .txt
         // $token_seleksi = unserialize(file_get_contents('fiturseleksi4.txt'));
         //B. via database
         $fitur_seleksi = FiturSeleksi::get();
         $i = 0;
         foreach ($fitur_seleksi as $row) {
            $token_seleksi[$i] = $row->term;
            $i++;
         }
         
         //menghitung jumlah token seleksi
         $jumlah_token_seleksi = count($token_seleksi);

         $hasil_training_seleksi = array();
         
         
         for ($k=0; $k < $jumlah_token_seleksi; $k++) { 
              $kemunculan_di_valid = 0;
              $kemunculan_di_spam = 0;

              for ($j=0; $j < $jumlah_term_valid ; $j++) { 
                  if ($term_valid[$j] == $token_seleksi[$k]) {
                     $kemunculan_di_valid = $kemunculan_di_valid + 1;//menghitung kemunculan term di kelas valid
                  }
              }
              
              for ($j=0; $j < $jumlah_term_spam ; $j++) { 
                  if ($term_spam[$j] == $token_seleksi[$k]) {
                     $kemunculan_di_spam = $kemunculan_di_spam + 1;//menghitung kemunculan term di kelas spam
                 }
              }

              //menghitung probabilitas term terhadap kelas valid
              $prob_term_valid = ($kemunculan_di_valid + 1)/($jumlah_term_valid + $jumlah_token);

              //menghitung probabilitas term terhadap kelas spam
              $prob_term_spam = ($kemunculan_di_spam + 1)/($jumlah_term_spam + $jumlah_token);
              $hasil_training_seleksi[$k]['term']=$token_seleksi[$k];
              $hasil_training_seleksi[$k]['kemunculan']=$kemunculan_di_valid;
              $hasil_training_seleksi[$k]['kemunculan_spam']=$kemunculan_di_spam;
              $hasil_training_seleksi[$k]['prob_valid']=round($prob_term_valid,10);
              $hasil_training_seleksi[$k]['prob_spam']=round($prob_term_spam,10);


              $index_seleksi = $k+1;
              #menyimpan hasil training ke database
              // $probabilitas_seleksi = FiturSeleksi::find($index_seleksi);
              // $probabilitas_seleksi->prob_valid_m = round($prob_term_valid,10);
              // $probabilitas_seleksi->prob_spam_m = round($prob_term_spam,10);
              // $probabilitas_seleksi->save();
         }


         

         //memanggil token hasil praproses
         // $token = $this->praproses();
         return View::make('training.multinomial')->with('hasil_training',$hasil_training)
         ->with('hasil_training_seleksi',$hasil_training_seleksi)
         ->with('jumlah_term_valid',$jumlah_term_valid)
         ->with('jumlah_term_spam',$jumlah_term_spam)
         ->with('jumlah_token',$jumlah_token); 
    }

  
	

   

    #FUNGSI FUNGSI YANG DIPANGGIL

    

    public function maketoken_fromarray($data_tweets){
          #seluruh tweet
         
         $i=0;
       
         $token= array();
         $jumlah_tweet = count($data_tweets);
        
         for ($i=0; $i < $jumlah_tweet; $i++) { 
            $terms = explode(" ", strtolower($data_tweets[$i])); //memecah tweet menjadi kata 
            $jumlah_term = count($terms);
            for ($j=0; $j < $jumlah_term ; $j++) { 
              //memasukkan term ke dalam daftar token
              //melakukan stemming untuk term  
              array_push($token, $terms[$j]);
            }  

         }
         return $token;

    }

    public function maketoken($data_tweets){
          #seluruh tweet
         
         $i=0;
         $jumlah_term_spam = 0;
         $jumlah_term_valid = 0;
         $token= array();
         $token_spam= array();
         $token_valid= array();
         foreach ($data_tweets as $row){
            $terms = explode(" ", strtolower($row['tweet'])); //memecah tweet menjadi kata 
            $jumlah_term = count($terms);
            //tidak ada kaitannnya dengan token
            // if ($row->kelas == 1) { //kalau kelas tweet adalah valid
            //     $jumlah_term_valid = $jumlah_term_valid + $jumlah_term;//menghitung jumlah term valid
            //     for ($j=0; $j < $jumlah_term ; $j++) { 
            //         array_push($token_valid, $terms[$j]);//memasukkan term ke dalam daftar token valid
            //     }  
            // }else{ //kalau kelas tweet adalah spam
            //     $jumlah_term_spam = $jumlah_term_spam + $jumlah_term;//menghitung jumlah term spam
            //     for ($j=0; $j < $jumlah_term ; $j++) { 
            //         array_push($token_spam, $terms[$j]);//memasukkan term ke dalam daftar token spam
            //     }  
            // }   

            for ($j=0; $j < $jumlah_term ; $j++) { 
              //memasukkan term ke dalam daftar token
              //melakukan stemming untuk term  
              array_push($token, $terms[$j]);
            }  

            
         }
         return $token;

    }



    //fungsi untuk merapikan token
    public function merapikan_token($input){
        //menghapus token yang sama
        $token_rapi = array_unique($input);
        //menghapus elemen array
        $token_rapi = array_filter($token_rapi);  
        //mengurutkan indeks array 
        $token_rapi = array_values($token_rapi);
      
        return $token_rapi;

    }

    //fungsi untuk merapikan term
    public function merapikan_term($input){
        
        //menghapus elemen array yang kosong
        $term_rapi = array_filter($input);  
        //mengurutkan indeks array 
        $term_rapi = array_values($term_rapi);
      
        return $term_rapi;

    }

    public function removeCommonWords($input){
            
          $commonWords = array('yang','di','ke','dari','dan');
          return preg_replace('/\b('.implode('|',$commonWords).')\b/','',$input);
    }

    public function removeUsernameHashtag($input){
            
            $input = preg_replace('/#([\w-]+)/i', '', $input); // menghapus @username
            $input = preg_replace('/@([\w-]+)/i', '', $input); // menghapus #tag
            return $input;
    }



    public function hapusURL($input){
            
              $urlRegex = '~(?i)\b((?:[a-z][\w-]+:(?:/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))~';
           
              $hasilremoval = preg_replace($urlRegex, '', $input); // menghapus URL
              return $hasilremoval;
    }

    public function fungsiHapussimbol($str){
            
            $str = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $str);//menghapus simbol
            $str = preg_replace('/[0-9]+/', '', $str);//menghapus angka yang ada
            return $str;
    }

      

}


	