<?php

class PraprosesController extends BaseController {

      #strtolower
    public function getStrtolower(){
          $data_trainings = Datatraining::get();
         return View::make('praproses.strtolower')->with('data_trainings',$data_trainings);
         // return View::make('skripsi.strtolower'); 
    }

    #tokenization
    public function getTokenization(){
        

         $data_tweets = Datatraining::get(); //meretrieve seluruh tweet
        
         $token = $this->maketoken($data_tweets);
         
        
         return View::make('praproses.tokenization')->with('token',$token); 
    }



    #proses hapus username dan hashtag
    public function getRemoveUsername(){

         $data_tweets = Datatraining::get(); //meretrieve seluruh tweet
        
         $token=$this->maketoken($data_tweets);

         $jumlah_token = count($token);//menghitung token
         $token_baru = array(); //mendefinisikan array token
         for ($i=0; $i < $jumlah_token; $i++) { 
              $str = $token[$i];
              
              $str = $this->removeUsernameHashtag($str); //panggil fungsi remove username dan hashtag
              array_push($token_baru, $str); // memasukkan ke array
         }
         
         $token_baru = $this->merapikan_token($token_baru);
         return View::make('praproses.removeusername')->with('token',$token)->with('token_baru',$token_baru); 
    }
    #proses penghapusan URL
    public function getRemoveURL(){

         $data_tweets = Datatraining::get(); //meretrieve seluruh tweet 
         $token = $this->maketoken($data_tweets);

         $jumlah_token = count($token);//menghitung token
         $token_baru = array(); //mendefinisikan array token
         $token_lama = array(); //mendefinisikan array token
         for ($i=0; $i < $jumlah_token; $i++) { 
              $str = $this->removeUsernameHashtag($token[$i]);//panggil fungsi remove username dan hashtag
              array_push($token_lama,$str);
              $str = $this->hapusURL($str);//menghapus URL

              array_push($token_baru, $str); // memasukkan ke array
         }
          $token_lama = $this->merapikan_token($token_lama);  
          $token_baru = $this->merapikan_token($token_baru);
          
          return View::make('praproses.removeurl')->with('token',$token_lama)->with('token_baru',$token_baru); 
    }

      #penghapusan simbol dan angka
      public function getRemovesymbol(){
        //remove number and symbol from string
         $data_tweets = Datatraining::get(); //meretrieve seluruh tweet 
         $token = $this->maketoken($data_tweets);

         $jumlah_token = count($token);//menghitung token
         $token_baru = array(); //mendefinisikan array token
         $token_lama = array(); //mendefinisikan array token
         for ($i=0; $i < $jumlah_token; $i++) { 
              $str = $this->removeUsernameHashtag($token[$i]);//panggil fungsi remove username dan hashtag
              
              $str = $this->hapusURL($str);//menghapus URL
              array_push($token_lama,$str);
              $str = $this->fungsiHapussimbol($str);//hapus simbol dan angka
              
              array_push($token_baru, $str); // memasukkan ke array
         }
         $token_lama = $this->merapikan_token($token_lama);  
         $token_baru = $this->merapikan_token($token_baru);
                   
          return View::make('praproses.removesymbol')->with('token',$token_lama)->with('token_baru',$token_baru);

      
        
     
    }

    #proses menghapus stopword
    public function getStopword(){
         
          $data_tweets = Datatraining::get(); //meretrieve seluruh tweet 
          $token = $this->maketoken($data_tweets);

          $token_baru=array();
          $token_lama =array();
          $count = count($token);
          for ($i=0; $i < $count; $i++) { 
            $str = $this->removeUsernameHashtag($token[$i]);//panggil fungsi remove username dan hashtag
            $str = $this->hapusURL($str);//menghapus URL
            $str = $this->fungsiHapussimbol($str);//hapus simbol dan angka
            array_push($token_lama,$str);//menyimpan ke token lama
            $str= $this->removeCommonWords($str);//memanggil fungsi untuk menghapus stopword
            array_push($token_baru, $str);
          }
          
          $token_lama = $this->merapikan_token($token_lama);//merapikan token lama
          $token_baru = $this->merapikan_token($token_baru);//merapikan token
          
    
          return View::make('praproses.stopword')->with('token_baru',$token_baru)->with('token',$token_lama);
    }

    #proses stemming
    public function getStemmer(){
         $stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();//load library stemmer sastrawi
         $stemmer  = $stemmerFactory->createStemmer();//load library stemmer sastrawi
          
         //1. Menampilkan data training saja
         // $fitur_praproses = FiturPraproses::get();

         // $i=0;
         // foreach ($fitur_praproses as $row) {
         //   $fitur[$i] = $row->term;
         //   $i++;
         // }

         // return View::make('praproses.show_stemming')->with('fitur',$fitur);


         //2. Melakukan proses training
         $random_valid = Datatraining::where('kelas','=',1)->distinct()->orderBy(DB::raw('RAND()'))->take(200)->get();
         $random_spam = Datatraining::where('kelas','=',2)->distinct()->orderBy(DB::raw('RAND()'))->take(200)->get();
         


         $i = 0;
         foreach ($random_valid as $row) {
             
             $data_training[$i] = $row->tweet;
             $i++;
         }
         foreach ($random_spam as $row) {
             
             $data_training[$i] = $row->tweet;
             $i++;
         }
        
         // return dd($data_training);

         // $data_tweets = Datatraining::get(); //meretrieve seluruh tweet 
         
         $token = $this->maketoken_fromarray($data_training);
         
         

         $jumlah_token = count($token);//menghitung token
         $token_lama = array(); //mendefinisikan array token
         $token_stem = array();
         $token_terbaru = array();
         for ($i=0; $i < $jumlah_token; $i++) { 
              $str = $this->removeUsernameHashtag($token[$i]);//panggil fungsi remove username dan hashtag
              $str = $this->hapusURL($str);//menghapus URL
              $str = $this->fungsiHapussimbol($str);//hapus simbol dan angka
              $str= $this->removeCommonWords($str);
              array_push($token_lama, $str); // memasukkan ke array
              //memecah token yang ada 2 kata
              //stemming
              $str = $stemmer->stem($str);
              $terms = explode(" ",$str);
              
              $jumlah_term = count($terms);
              if ($jumlah_term > 1) {
                for ($j=0; $j < $jumlah_term ; $j++) { 
                    array_push($token_terbaru,$terms[$j]);
                }
              }else{
                  array_push($token_terbaru,$terms[0]);
              }

              // }
              // for ($j=0; $j < $jumlah_term ; $j++) { 
              //    array_push($token_terbaru,$terms[$j]);
              // }
              // array_push($token_stem, $stemmer->stem($str)); // memasukkan ke array
         }

        
         $token_lama = $this->merapikan_token($token_lama);
         // $token_stem = $this->merapikan_token($token_stem);
         $token_terbaru = $this->merapikan_token($token_terbaru);

         $data_tabel_praproses = FiturPraproses::count('id');
         // return dd($data_tabel_praproses); 
         if ($data_tabel_praproses != 0) {
             //menghapus data jika ada di tabel fitur praproses
             $hapus_data = FiturPraproses::truncate();
         }

         $jumlah_token_terbaru = count($token_terbaru);
         for ($i=0; $i < $jumlah_token_terbaru ; $i++) { 
             $token_praproses = new FiturPraproses;
             $token_praproses->term = $token_terbaru[$i];
             $token_praproses->save();
         }
         

         return View::make('praproses.stemming')->with('token_lama',$token_lama)->with('token_stem',$token_stem)->with('token_terbaru',$token_terbaru); 
    
    }

    #menampilkan hasil praproses
    public function HasilPraproses(){
         //Menampilkan token dari hasil praproses
         $fitur_praproses = FiturPraproses::get();

         $i=0;
         foreach ($fitur_praproses as $row) {
           $fitur[$i] = $row->term;
           $i++;
         }

         return View::make('praproses.hasil')->with('fitur',$fitur);
    }

  
  

      

}


  