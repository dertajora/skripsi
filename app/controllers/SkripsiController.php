<?php

class SkripsiController extends BaseController {

	  public function getHome(){

         $data_training2 = Datatraining2::get();
         // $data_training = Datatraining::get();
         // $data_uji = Datauji::get();
         // $i = 0;
         // foreach ($data_training as $row ) {
         //     $data_train[$i]=$row->tweet;
         //     $i+=1;
         // }

         // $i = 0;
         // foreach ($data_training2 as $row ) {
         //     $data_train2[$i]=$row->tweet;
         //     $i+=1;
         // }

         // $i = 0;
         // foreach ($data_uji as $row ) {
         //     $data_tes[$i]=$row->tweet;
         //     $i+=1;
         // }
         // // print_r($data_train2);
         // // return "";
         // $array1 = array("a" => "green", "red", "blue");
         // $array2 = array("b" => "green", "yellow", "red");
         // $result = array_intersect($data_tes, $data_train);
         // return count($result);
         // $stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();//load library stemmer sastrawi
         // $stemmer  = $stemmerFactory->createStemmer();//load library stemmer sastrawi
          
         // $derta = $stemmer->stem("cinta terlarang");
         // return $derta;
        // $filename = "array.json";
        // $contents = File::get($filename);
        // $file = 'derta.txt';
        // $array = array(array('id'=>1,'nama'=>'derta','prob_m'=>0.34),array('id'=>2,'nama'=>'kuncung','prob_m'=>0.34));

        // $array1 = array(array('id'=>3,'nama'=>'derta','prob_m'=>0.34),array('id'=>2,'nama'=>'kuncung','prob_m'=>0.34));
        // $array_baru = array(array('id'=>4,'nama'=>'kuncung','prob_m'=>0.34));
        // array_push($array1, $array_baru); // memasukkan ke array
        // // file_put_contents($file, serialize($array));

        // $fitur_praproses = FiturPraproses::get();

        //  $i=0;
        //  foreach ($fitur_praproses as $row) {
        //    $fitur[$i] = $row->term;
        //    $i++;
        //  }

        // // file_put_contents("array.json",json_encode($array));
        // file_put_contents("array.json",json_encode($fitur));

        // $arr2 = json_decode(file_get_contents('array.json'), true);
        // return dd($arr2);
        // $str = " asaaaass aasss sssaa aabb";
        // $new_str = preg_replace('/(.)\1{2,}/', "$1", $str);
        // return $new_str;  
        // return  preg_replace('/(([^\d])\2\2)\2+/', '$1', $str);
        // return preg_replace('/(.)(?=.*?\1)/', '', 'aaabbbabcc');
        
        // // //file_put_contents($file, $current); //tunggal
        // // $contents = File::get($filename);
        // // return $contents; 
        
        
      
         return View::make('skripsi.home'); 
    }

    public function getDatatable(){
      $data_trainings = Datauji::orderBy('kelas_asli')->get();
      return View::make('data.datatable')->with('data_trainings',$data_trainings);
      return View::make('skripsi.coba');
        
         return View::make('skripsi.datatable')->with('data_trainings',$data_trainings);
         
    }

    public function getDatatraining(){
    	   $stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();//load library stemmer sastrawi
         $stemmer  = $stemmerFactory->createStemmer();//load library stemmer sastrawi
         $data_trainings = Datatraining::orderBy('kelas')->get(); //menampilkan data training
         return View::make('data.data-training')->with('data_trainings',$data_trainings);
         // return View::make('skripsi.data-training'); 
    }

    public function getDatatrainingDua(){
         $stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();//load library stemmer sastrawi
         $stemmer  = $stemmerFactory->createStemmer();//load library stemmer sastrawi
         $data_trainings = Datatraining2::orderBy('kelas')->get(); //menampilkan data training
         return View::make('data.data-training-2')->with('data_trainings',$data_trainings);
         // return View::make('skripsi.data-training'); 
    }

    public function getDatauji(){
    	 $data_ujis = Datauji::orderBy('kelas_asli')->get(); //menampilkan data uji
       return View::make('data.data-uji')->with('data_ujis',$data_ujis);
         // return View::make('skripsi.data-uji'); 
    }
    

    

    #PRAPROSES

    #strtolower
    public function getStrtolower(){
          $data_trainings = Datatraining2::orderBy('kelas')->get();
         return View::make('praproses.strtolower')->with('data_trainings',$data_trainings);
         // return View::make('skripsi.strtolower'); 
    }

    #tokenization
    public function getTokenization(){
        

         $data_tweets = Datatraining::get(); //meretrieve seluruh tweet
         
         // $array = unserialize(file_get_contents('token.json'));
         // $arr2 = json_decode(file_get_contents('token.json'), true);
         // return dd($arr2);

         $token = $this->maketoken($data_tweets);
         $token = $this->merapikan_token($token);
         // file_put_contents("token.json",serialize($token));
         file_put_contents("token4.txt", serialize($token));
        
         return View::make('praproses.tokenization')->with('token',$token); 
    }



    #proses hapus username dan hashtag
    public function getRemoveUsername(){

         // $data_tweets = Datatraining::get(); //meretrieve seluruh tweet
        
         // $token=$this->maketoken($data_tweets);
         $token = unserialize(file_get_contents('token4.txt'));

         $jumlah_token = count($token);//menghitung token
         $token_baru = array(); //mendefinisikan array token
         for ($i=0; $i < $jumlah_token; $i++) { 
              $str = $token[$i];
              
              $str = $this->removeUsernameHashtag($str); //panggil fungsi remove username dan hashtag
              array_push($token_baru, $str); // memasukkan ke array
         }
         
         $token_baru = $this->merapikan_token($token_baru);
         file_put_contents("token4.txt", serialize($token_baru));
         return View::make('praproses.removeusername')->with('token',$token)->with('token_baru',$token_baru); 
    }
    #proses penghapusan URL
    public function getRemoveURL(){

         // $data_tweets = Datatraining::get(); //meretrieve seluruh tweet 
         // $token = $this->maketoken($data_tweets);
         $token = unserialize(file_get_contents('token4.txt'));
         $jumlah_token = count($token);//menghitung token
         $token_baru = array(); //mendefinisikan array token
         $token_lama = array(); //mendefinisikan array token
         for ($i=0; $i < $jumlah_token; $i++) { 
              // $str = $this->removeUsernameHashtag($token[$i]);//panggil fungsi remove username dan hashtag
              array_push($token_lama,$token[$i]);
              $str = $this->hapusURL($token[$i]);//menghapus URL

              array_push($token_baru, $str); // memasukkan ke array
         }
          $token_lama = $this->merapikan_token($token_lama);  
          $token_baru = $this->merapikan_token($token_baru);
          file_put_contents("token4.txt", serialize($token_baru));
          return View::make('praproses.removeurl')->with('token_lama',$token_lama)->with('token_baru',$token_baru); 
    }

      #penghapusan simbol dan angka
      public function getRemovesymbol(){
        //remove number and symbol from string
         // $data_tweets = Datatraining::get(); //meretrieve seluruh tweet 
         // $token = $this->maketoken($data_tweets);
         $token = unserialize(file_get_contents('token4.txt'));
         $jumlah_token = count($token);//menghitung token
         $token_baru = array(); //mendefinisikan array token
         $token_lama = array(); //mendefinisikan array token
         for ($i=0; $i < $jumlah_token; $i++) { 
              // $str = $this->removeUsernameHashtag($token[$i]);//panggil fungsi remove username dan hashtag
              
              // $str = $this->hapusURL($str);//menghapus URL
              array_push($token_lama,$token[$i]);
              $str = $this->fungsiHapussimbol($token[$i]);//hapus simbol dan angka
              
              array_push($token_baru, $str); // memasukkan ke array
         }
         $token_lama = $this->merapikan_token($token_lama);  
         $token_baru = $this->merapikan_token($token_baru);
         file_put_contents("token4.txt", serialize($token_baru));
          return View::make('praproses.removesymbol')->with('token',$token_lama)->with('token_baru',$token_baru);

      
        
     
    }

    #proses normalisasi kata
    public function getNormalisasi(){

          // $data_tweets = Datatraining::get(); //meretrieve seluruh tweet 
          // $token = $this->maketoken($data_tweets);
          $token = unserialize(file_get_contents('token4.txt'));
          $token_baru=array();
          $token_lama =array();
          $count = count($token);
          for ($i=0; $i < $count; $i++) { 
            // $str = $this->removeUsernameHashtag($token[$i]);//panggil fungsi remove username dan hashtag
            // $str = $this->hapusURL($str);//menghapus URL
            // $str = $this->fungsiHapussimbol($str);//hapus simbol dan angka
            array_push($token_lama,$token[$i]);//menyimpan ke token lama
            $str= $this->normalisasiKata($token[$i]);//memanggil fungsi untuk menghapus stopword
            array_push($token_baru, $str);
          }
          
          $token_lama = $this->merapikan_token($token_lama);//merapikan token lama
          $token_baru = $this->merapikan_token($token_baru);//merapikan token
          file_put_contents("token4.txt", serialize($token_baru));
    
          return View::make('praproses.normalisasi')->with('token_baru',$token_baru)->with('token',$token_lama);
           
        
    }


    #proses menghapus stopword
    public function getStopword(){
         
          // $data_tweets = Datatraining::get(); //meretrieve seluruh tweet 
          // $token = $this->maketoken($data_tweets);
          $token = unserialize(file_get_contents('token4.txt'));
          $token_baru=array();
          $token_lama =array();
          $count = count($token);
          for ($i=0; $i < $count; $i++) { 
            // $str = $this->removeUsernameHashtag($token[$i]);//panggil fungsi remove username dan hashtag
            // $str = $this->hapusURL($str);//menghapus URL
            // $str = $this->fungsiHapussimbol($str);//hapus simbol dan angka
            array_push($token_lama,$token[$i]);//menyimpan ke token lama
            $str= $this->removeCommonWords($token[$i]);//memanggil fungsi untuk menghapus stopword
            array_push($token_baru, $str);
          }
          
          $token_lama = $this->merapikan_token($token_lama);//merapikan token lama
          $token_baru = $this->merapikan_token($token_baru);//merapikan token
          file_put_contents("token4.txt", serialize($token_baru));
    
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
         // $random_valid = Datatraining::where('kelas','=',1)->distinct()->get();
         
         // $random_spam = Datatraining::where('kelas','=',2)->distinct()->get();
         


         // $i = 0;
         // foreach ($random_valid as $row) {
             
         //     $data_training[$i] = $row->tweet;
         //     $i++;
         // }
         // foreach ($random_spam as $row) {
             
         //     $data_training[$i] = $row->tweet;
         //     $i++;
         // }
        
         // return dd(count($data_training));

         // $data_tweets = Datatraining::get(); //meretrieve seluruh tweet 
         $token = unserialize(file_get_contents('token4.txt'));
         // $token = $this->maketoken_fromarray($data_training);
         
         

         $jumlah_token = count($token);//menghitung token

         $token_lama = array(); //mendefinisikan array token
         $token_stem = array();
         $token_terbaru = array();
         for ($i=0; $i < $jumlah_token; $i++) { 
              // $str = $this->removeUsernameHashtag($token[$i]);//panggil fungsi remove username dan hashtag
              // $str = $this->hapusURL($str);//menghapus URL
              // $str = $this->fungsiHapussimbol($str);//hapus simbol dan angka
              // $str= $this->removeCommonWords($str);
              array_push($token_lama, $token[$i]); // memasukkan ke array token lama
              //memecah token yang ada 2 kata
              //stemming
              $str = $stemmer->stem($token[$i]);
              $terms = explode(" ",$str);
              
              $jumlah_term = count($terms);
              if ($jumlah_term > 1) {
                for ($j=0; $j < $jumlah_term ; $j++) { 
                    array_push($token_terbaru,$terms[$j]);
                }
              }else{
                  array_push($token_terbaru,$terms[0]);
              }

             
         }



        
         $token_lama = $this->merapikan_token($token_lama);
         
         $token_terbaru = $this->merapikan_token($token_terbaru);

         file_put_contents("token4.txt", serialize($token_terbaru));
         $data_tabel_praproses = FiturPraproses::count('id');
         
         if ($data_tabel_praproses != 0) {
             // menghapus data jika ada di tabel fitur praproses
             // $hapus_data = FiturPraproses2::truncate();
         }

         $jumlah_token_terbaru = count($token_terbaru);
         for ($i=0; $i < $jumlah_token_terbaru ; $i++) { 
             // untuk menyimpan token ke database
             // $token_praproses = new FiturPraproses2;
             // $token_praproses->term = $token_terbaru[$i];
             // $token_praproses->save();
         }
         

         return View::make('praproses.stemming')->with('token_lama',$token_lama)->with('token_stem',$token_stem)->with('token_terbaru',$token_terbaru); 
    
    }

    #menampilkan hasil praproses
    public function HasilPraproses(){
         //Menampilkan token dari hasil praproses
         // $fitur_praproses = FiturPraproses::get();


         // #load database 
         // $i=0;
         // foreach ($fitur_praproses as $row) {
         //   $fitur[$i] = $row->term;
         //   $i++;
         // }
         
         // $file = "derta.txt";
         // file_put_contents($file, serialize($fitur)); 
         // #load file  
         $fitur = unserialize(file_get_contents('token4.txt'));
  

         return View::make('praproses.hasil')->with('fitur',$fitur);
    }
    

   

    #fungsi fungsi yang dipanggil

    

    public function maketoken_fromarray($data_tweets){
          #seluruh tweet
         
         $i=0;
         

         $token= array();
         $jumlah_tweet = count($data_tweets);
         //untuk setiap tweet 
         for ($i=0; $i < $jumlah_tweet; $i++) { 
            //memecah tweet menjadi kata 
            $terms = explode(" ", strtolower($data_tweets[$i])); 
            $jumlah_term = count($terms);
            for ($j=0; $j < $jumlah_term ; $j++) { 
              //memasukkan term ke dalam daftar token
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

    public function stemming($input){
         $stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();//load library stemmer sastrawi
         $stemmer  = $stemmerFactory->createStemmer();//load library stemmer sastrawi
          
         return $stemmer->stem($input);

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

    public function normalisasiKata($input){
        $input = preg_replace('/(.)\1{2,}/', "$1", $input);

        $cek_tidak_baku = TabelKata::where('sebelum','=',$input)->pluck('sebelum');
        if ($cek_tidak_baku != null) {
          $kata_normal = TabelKata::where('sebelum','=',$input)->pluck('sesudah');
        }else{
          $kata_normal = $input;
        }
        return $kata_normal;

    }

    public function removeCommonWords($input){
            
          $commonWords = array('ada','adalah','agaknya','agar','aku','amat','apa','apabila','apalagi','atas','atau',
                               'bahwa','bahkan','baik','bakal','begini','belum','berada','berikut','bila','biasa','bukan','bukanlah',
                               'bukankah','cuma','dahulu','dalam','dan','dapat','daripada','dapat','demi','demikian','dengan','dia',
                               'entah','hampir','hanya','hanyalah','harus','ini','itu','jadi','jangan','jika','jikalau','juga',
                               'kalau','kan','karena','kenapa','kepada','kembali','kebetulan','kelihatan','kelihatannya','ketika',
                               'kini','kinilah','kira','kira-kira','lah','lain','lainnya','lagi','lama',
                               'lanjut','maka','makin','malah','malahan','mana','manakala','manalagi','masih',
                               'melainkan','nanti','nyaris','oleh','olehnya','padahal','paling','rt',
                               'saat','saja','sambil','sangat','sebab','sebagai','sering','sudah','segera','sudah',
                               'tentu','tentang','tentu','tetapi','terdapat','walau','walaupun','yang','yaitu');
          return preg_replace('/\b('.implode('|',$commonWords).')\b/','',$input);
    }

    public function removeUsernameHashtag($input){
            // menghapus hashtag
            $input = preg_replace('/#([\w-]+)/i', '', $input); 
             // menghapus username
            $input = preg_replace('/@([\w-]+)/i', '', $input);
            return $input;
    }



    public function hapusURL($input){
              
              #pola URL alternatif
              // $pattern = '#(www\.|https?://)?[a-z0-9]+\.[a-z0-9]{2,4}\S*#i';
              // $hasilremoval = preg_replace($pattern, '', $input); // menghapus URL
              
              $urlRegex = '~(?i)\b((?:[a-z][\w-]+:(?:/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))~';
           
              $hasilremoval = preg_replace($urlRegex, '', $input); // menghapus URL
              return $hasilremoval;
    }

    public function fungsiHapussimbol($str){

            //menghapus simbol
            $str = preg_replace('/[^\p{L}\p{N}\s]/u', ' ', $str);
            //menghapus angka yang ada
            $str = preg_replace('/[0-9]+/', '', $str);
            return $str;
    }
    public function praproses(){
         $stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();//load library stemmer sastrawi
         $stemmer  = $stemmerFactory->createStemmer();//load library stemmer sastrawi
          
         

         $data_tweets = Datatraining::get(); //meretrieve seluruh tweet 
         $token = $this->maketoken($data_tweets);
        
        

         $jumlah_token = count($token);//menghitung token
         $token_praproses = array(); //mendefinisikan array token
         
         for ($i=0; $i < $jumlah_token; $i++) { 
              $str = $this->removeUsernameHashtag($token[$i]);//panggil fungsi remove username dan hashtag
              $str = $this->hapusURL($str);//menghapus URL
              $str= $this->removeCommonWords($str);//menghapus stopword
              $str = $this->fungsiHapussimbol($str);//hapus simbol dan angka
              
              array_push($token_praproses, $stemmer->stem($str)); // memasukkan ke array
         }

         $token_praproses = $this->merapikan_token($token_praproses);//merapikan token
         
         return $token_praproses;
         
    }
  
          
    
    #proses seleksi fitur
    public function getChi(){
        // return View::make('skripsi.chi_square');
         $jumlah_spam = Datatraining::where('kelas','=',2) //menghitung tweet spam
                ->count('id');
         $jumlah_valid = Datatraining::where('kelas','=',1) //menghitung tweet valid
                ->count('id'); 
         $jumlah_tweet = Datatraining::count('id');//menghitung jumlah tweet    

         $data_valids = Datatraining::where('kelas','=',1)->get(); //meretrieve tweet valid
         
         $data_spams = Datatraining::where('kelas','=',2)->get(); //meretrieve tweet spam

        //1. Menampilkan token hasil seleksi saja
         $fitur_seleksi = FiturSeleksi::get();
         
         $i=0;
         foreach ($fitur_seleksi as $row) {
           $fitur[$i] = $row->term;
           $i++;
         }
         $fitur = array();

         // return View::make('praproses.show_seleksi')->with('fitur',$fitur);


         //memanggil token hasil praproses
         //$token = $this->praproses();
         $fitur_praproses = FiturPraproses::get();
         $i=0;
         foreach ($fitur_praproses as $row) {
           $token[$i] = $row->term;
           $i++;
         } 

         //menghitung jumlah token hasil praproses
         $jumlah_token = count($token);
         //mendefinisikan array baru
         $token_seleksi = array();
         $token_keren = array();
         // return dd($token);
         for ($i=0; $i < $jumlah_token; $i++) { 
             $kata = $token[$i];
             $jumlah_A = 0;
             $jumlah_B = 0;
             //menghitung jumlah tweet valid yang mengandung token ke-i
             foreach ($data_valids as $row) {
                if (strpos(strtolower($row->tweet), $kata) !== false) {
                $jumlah_A = $jumlah_A + 1;
                }
             }
             //menghitung jumlah tweet spam yang mengandung token ke-i
             foreach ($data_spams as $row) {
                if (strpos(strtolower($row->tweet), $kata) !== false) {
                $jumlah_B = $jumlah_B + 1;
                }
             }
             #mencari jumlah dokumen pada kelas valid yang tidak mengandung term X
             $jumlah_C = $jumlah_valid-$jumlah_A;
             #mencari jumlah dokumen pada kelas spam yang tidak mengandung term X
             $jumlah_D = $jumlah_spam-$jumlah_B;

             $penyebut = ($jumlah_A + $jumlah_B) * ( $jumlah_C+$jumlah_D )* ($jumlah_A+$jumlah_C) * ($jumlah_B+$jumlah_D); //mencari penyebut 
             if ($penyebut == 0) {
                 $nilai_chi = 0;
             }else{
             $pembilang_kanan = (($jumlah_A*$jumlah_D)-($jumlah_B*$jumlah_C)); //mencari pembilang bagian kanan untuk persamaan mencari nilai chi square 
             $nilai_chi = $jumlah_tweet * pow($pembilang_kanan,2) / $penyebut;  //menghitung nilai chi square
             }
             //menyimpan nilai A,B,C,D, Chi Square dan term ke dalam array
             $token_seleksi[$i]['token'] = $kata;
             $token_seleksi[$i]['jumlah_A'] = $jumlah_A;
             $token_seleksi[$i]['jumlah_B'] = $jumlah_B;
             $token_seleksi[$i]['jumlah_C'] = $jumlah_C;
             $token_seleksi[$i]['jumlah_D'] = $jumlah_D;
             $token_seleksi[$i]['nilai_chi'] = $nilai_chi;

             //jika nilai chi square memenuhi treshold masukkan ke array token_baru
             if ($token_seleksi[$i]['nilai_chi']>2.71) {
                 $token_baru[$i] = $kata ;
                 // $token_baru[$i]['nilai_chi'] = $token_seleksi[$i]['nilai_chi'];
             }
             if ($token_seleksi[$i]['nilai_chi']>6.63) {
                 $token_keren[$i] = $kata ;
                 // $token_baru[$i]['nilai_chi'] = $token_seleksi[$i]['nilai_chi'];
             }
         }
         
         //merapikan token
         $token_baru = $this->merapikan_token($token_baru);

         return View::make('seleksi.chi_square')->with('token_seleksi',$token_seleksi)
                                                ->with('token_baru',$token_baru)
                                                ->with('token',$token)
                                                ->with('token_keren',$token_keren); 
    }
    #proses pelatihan data training multinomial biasa
    public function getTrainingMultinomial(){
        //kecepatan cahaya
        // return View::make('skripsi.hasil-training');
         $stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();//load library stemmer sastrawi
         $stemmer  = $stemmerFactory->createStemmer();//load library stemmer sastrawi
        //cara lambat
         $jumlah_spam = Datatraining::where('kelas','=',2) //menghitung tweet spam
                ->count('id');
         $jumlah_valid = Datatraining::where('kelas','=',1) //menghitung tweet valid
                ->count('id'); 
         $jumlah_tweet = Datatraining::count('id');//menghitung jumlah tweet    

         $data_valids = Datatraining::where('kelas','=',1)->get(); //meretrieve tweet valid
         $data_spams = Datatraining::where('kelas','=',2)->get(); //meretrieve tweet spam
         $data_tweets = Datatraining::get(); //meretrieve seluruh tweet
         
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
                array_push($term_valid, $terms_valid[$j]);//memasukkan term ke dalam daftar token valid
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
                $term = $terms_spam[$j];
                $term = $this->removeUsernameHashtag($term);
                $term = $this->hapusURL($term);
                $term = $this->fungsiHapussimbol($term);
                array_push($term_spam, $term);//memasukkan term ke dalam daftar term spam
             }
         }

         //menghapus term spasi
         $term_spam = $this->merapikan_term($term_spam);
         #menghitung jumlah term kelas spam , spasi ndak dihitung
         $jumlah_term_spam = count($term_spam);
         

         #memanggil token hasil praproses, selajutnya token hasil seleksi, 
         // $token = $this->praproses();
         // list($token_matematis,$token) = $this->fungsiSeleksi($token);
         
         $fitur_seleksi = FiturSeleksi::get();
         $i = 0;
         foreach ($fitur_seleksi as $row) {
            $token[$i] = $row->term;
            $i++;
         }
         echo "<pre>";
         print_r($term_spam);
         echo "</pre>";
         return "sukses";
         return dd($term_spam);
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

    #proses pelatihan data training bernoulli biasa
    public function getTrainingBernoulli(){
         $stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();//load library stemmer sastrawi
         $stemmer  = $stemmerFactory->createStemmer();//load library stemmer sastrawi
         
         $data_tweets = Datatraining::get(); //meretrieve seluruh tweet
         $tweets_valid = Datatraining::where('kelas','=',1)->get();
         $tweets_spam = Datatraining::where('kelas','=',2)->get();

         $jumlah_tweet_valid = Datatraining::where('kelas','=',1)->count('id');
         $jumlah_tweet_spam = Datatraining::where('kelas','=',2)->count('id');
         $token = $this->praproses();
         list($token_matematis,$token) = $this->fungsiSeleksi($token);
         $jumlah_token = count($token);
         $hasil_training = array();

         for ($i=0; $i < $jumlah_token; $i++) { 
              $happened_valid = 0;
              $happened_spam = 0;


              foreach ($tweets_valid as $row) {
                  //praproses tweet
                  $tweet = strtolower($row->tweet);
                  $tweet = $this->removeUsernameHashtag($tweet);
                  $tweet = $this->hapusURL($tweet);
                  $tweet = $this->fungsiHapussimbol($tweet);
                  $tweet = $stemmer->stem($tweet);

                  if (strpos($tweet,$token[$i]) !== false) { // mengecek tweet pada kelas valid mengandung term x tidak
                     $happened_valid = $happened_valid+1; // jika mengandung maka jumlah kelas c yang mengandung x ditambah 1
                  }
              }

              foreach ($tweets_spam as $row) {

                  //praproses tweet
                  $tweet = strtolower($row->tweet);
                  $tweet = $this->removeUsernameHashtag($tweet);
                  $tweet = $this->hapusURL($tweet);
                  $tweet = $this->fungsiHapussimbol($tweet);
                  $tweet = $stemmer->stem($tweet);

                  if (strpos($tweet,$token[$i]) !== false) { // mengecek tweet pada kelas spam mengandung term x tidak
                     $happened_spam = $happened_spam+1; // jika mengandung maka jumlah kelas c yang mengandung x ditambah 1
                  }
              }
            
              
              //menghitung probabilitas term terhadap kelas valid
              $prob_term_valid = ($happened_valid + 1)/($jumlah_tweet_valid + 2);

              //menghitung probabilitas term terhadap kelas spam
              $prob_term_spam = ($happened_spam + 1)/($jumlah_tweet_spam + 2);
              
              //memasukkan ke array hasil_training
              $hasil_training[$i]['term']=$token[$i];
              $hasil_training[$i]['happened_valid']=$happened_valid;
              $hasil_training[$i]['happened_spam']=$happened_spam;
              $hasil_training[$i]['prob_valid']=round($prob_term_valid,3);
              $hasil_training[$i]['prob_spam']=round($prob_term_spam,3);
              
              
         }

         //kembali ke view
         return View::make('training.bernoulli')->with('hasil_training',$hasil_training)
            ->with('jumlah_tweet_valid',$jumlah_tweet_valid)
            ->with('jumlah_tweet_spam',$jumlah_tweet_spam); 
    }

    #proses mengklasifikasikan satu data uji dengan multinomial
    public function KlasifikasiMultinomial(){
         $stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();//load library stemmer sastrawi
         $stemmer  = $stemmerFactory->createStemmer();//load library stemmer sastrawi

        $hasil_training = $this->fungsiMultinomial();//memanggil term hasil training
        
        $tweet_unclassified = "judi bola terpercaya terbaik togel jalan"; //data tes
        $tweet = $this->removeUsernameHashtag($tweet_unclassified);
        $tweet = $this->hapusURL($tweet);
        $tweet = $this->fungsiHapussimbol($tweet);
        $tweet = $stemmer->stem($tweet);

        //memecah data uji dan praproses
        $term_data_test = explode(" ", $stemmer->stem(strtolower($tweet)));
        $jumlah_term_uji = count($term_data_test);
        
        //membuat array untuk menyimpan token hasil training saja 
        $jumlah_token_training = count($hasil_training);
        for ($i=1; $i <= $jumlah_token_training ; $i++) { 
            $vocab[$i] = $hasil_training[$i-1]['term'];
        }
        
        
        //menentukan prior 
        $jumlah_spam = Datatraining::where('kelas','=',2) //menghitung tweet spam
                ->count('id');
        $jumlah_valid = Datatraining::where('kelas','=',1) //menghitung tweet valid
                ->count('id'); 
        $jumlah_tweet = Datatraining::count('id');//menghitung jumlah tweet   

        //prior probability
        $probabilitas_spam = $jumlah_spam/$jumlah_tweet;
        $probabilitas_valid = $jumlah_valid/$jumlah_tweet;
       
        // return dd($term_data_test);
        $pengali = array();
        for ($i=0; $i < $jumlah_term_uji; $i++) { 
            //mengecek apakah term data uji ada di token hasil training, sekaligus mengetahui indeksnya 
            $index_vocab = array_search($term_data_test[$i], $vocab);
            

           
            //kalau term data uji ada di token hasil training, maka probabilitas term di hasil training digunakan
            //kalau term data uji tidak ada di token hasil training, maka term data uji diabaikan
            if ($index_vocab != false) {
                $probabilitas_valid = $probabilitas_valid * $hasil_training[$index_vocab-1]['prob_valid'];
                $probabilitas_spam = $probabilitas_spam * $hasil_training[$index_vocab-1]['prob_spam'];
               
                array_push($pengali,$hasil_training[$index_vocab-1]['prob_spam']);
            }else{
                array_push($pengali,"kosong");
            }
           
        }


        

        //menentukan kelas data uji
        if($probabilitas_spam > $probabilitas_valid){
            $kelas = "spam" ;
        }elseif ($probabilitas_valid > $probabilitas_spam) {
            $kelas = "valid" ;
        }

      

        //mengembalikan ke view
        return View::make('klasifikasi.multinomialsatu')
            ->with('data_uji',$tweet_unclassified)
            ->with('kelas',$kelas)
            ->with('probabilitas_valid',$probabilitas_valid)
            ->with('probabilitas_spam',$probabilitas_spam)
            ->with('hasil_training',$hasil_training);
                   
        
    }

    #proses mengklasifikasikan satu data uji dengan model bernoulli
    public function klasifikasiBernoulli(){
        $stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();//load library stemmer sastrawi
        $stemmer  = $stemmerFactory->createStemmer();//load library stemmer sastrawi

        $hasil_training = $this->fungsiBernoulli();//memanggil term hasil training
        
        $tweet_unclassified = "derta isyajora laporan cewek seksi jalan perawan"; //data tes

        $tweet = $this->removeUsernameHashtag($tweet_unclassified);
        $tweet = $this->hapusURL($tweet);
        $tweet = $this->fungsiHapussimbol($tweet);
        $tweet = $stemmer->stem($tweet);
        //memecah data uji tweet menjadi token

        $term_data_test = explode(" ", $stemmer->stem(strtolower($tweet)));
        
        //merapikan token
        $term_data_test = $this->merapikan_token($term_data_test);
        
        
        //menghitung jumlah token dari hasil training
        $jumlah_token_training = count($hasil_training);
        
        #untuk mengkoreksi pengali
        // $pengali_baru = array();

        //menentukan prior 
        $jumlah_spam = Datatraining::where('kelas','=',2) //menghitung tweet spam
                ->count('id');
        $jumlah_valid = Datatraining::where('kelas','=',1) //menghitung tweet valid
                ->count('id'); 
        $jumlah_tweet = Datatraining::count('id');//menghitung jumlah tweet   

        //prior probability
        $probabilitas_valid = $jumlah_valid/$jumlah_tweet;
        $probabilitas_spam = $jumlah_spam/$jumlah_tweet;

        //mengecek untuk masing-masing token pada hasil training
        $pengali = array();
        for ($i=0; $i < $jumlah_token_training; $i++) { 
            //mengecek apakah token di hasil training terdapat pada data uji
            $check_in_data = in_array($hasil_training[$i]['term'],$term_data_test);    
            //jika ada, maka probabilitas token tersebut digunakan sebagai pengali
            if ($check_in_data == true)  {
                $pengali_valid = $hasil_training[$i]['prob_valid'];
                $pengali_spam = $hasil_training[$i]['prob_spam'];
                array_push($pengali,$hasil_training[$i]['prob_valid']);
            //jika tidak ada, maka (1 - probabilitas token tersebut) digunakan sebagai pengali
            }elseif ($check_in_data == false) {
                $pengali_valid = 1-$hasil_training[$i]['prob_valid'];
                $pengali_spam = 1-$hasil_training[$i]['prob_spam'];
                array_push($pengali,$pengali_valid);
            }
            #untuk mengkoreksi pengali
            

            //menghitung probabilitas data uji terhadap kelas valid
            $probabilitas_valid = $probabilitas_valid * $pengali_valid;
            $probabilitas_spam = $probabilitas_spam * $pengali_spam;
        }
        $pengali_valid = 1-$hasil_training[0]['prob_valid'];
        // return dd($hasil_training);
        // menentukan kelas
        if($probabilitas_spam > $probabilitas_valid){
            $kelas = "spam" ;
        }elseif ($probabilitas_valid > $probabilitas_spam) {
            $kelas = "valid" ;
        }

        //mengembalikan ke view
        return View::make('klasifikasi.bernoullisatu')
            ->with('data_uji',$tweet_unclassified)
            ->with('kelas',$kelas)
            ->with('probabilitas_valid',$probabilitas_valid)
            ->with('probabilitas_spam',$probabilitas_spam)
            ->with('hasil_training',$hasil_training);
    }



    #proses uji klasifikasi dengan multinomial
    public function UjiMultinomial(){
      
        $stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();//load library stemmer sastrawi
        $stemmer  = $stemmerFactory->createStemmer();//load library stemmer sastrawi

        $hasil_training = $this->fungsiMultinomial();//memanggil term hasil training
        //data tes
        $tweet_unclassified = array(array("Jalan, rusak  23232 http://bit.ly/derta di malioboro, tolong segera diperbaiki",1),
                                    array("cewek seksi pamer susu",2),
                                     array("cewek seksi video vulgar",1),
                                    array("ada jembatan jalan rusak di ugm",1),
                                    array("bantu promoto jalan cewek seksi ini guys",2),
                                    array("jalan jalan banyak yang merusak capek deh",1)
                                    );

      
        
        //membuat array untuk menyimpan token hasil training saja 
        $jumlah_token_training = count($hasil_training);
        for ($i=1; $i <= $jumlah_token_training ; $i++) { 
            $vocab[$i] = $hasil_training[$i-1]['term'];
        }
        
        //menentukan prior 
        $jumlah_spam = Datatraining::where('kelas','=',2) //menghitung tweet spam
                ->count('id');
        $jumlah_valid = Datatraining::where('kelas','=',1) //menghitung tweet valid
                ->count('id'); 
        $jumlah_tweet = Datatraining::count('id');//menghitung jumlah tweet   

        $jumlah_data_uji = count($tweet_unclassified);
        
        
        //melakukan perulangan klasifikasi untuk setiap data uji
        for ($j=0; $j < $jumlah_data_uji ; $j++) { 
            
        //memecah data uji dan praproses
        $tweet = strtolower($tweet_unclassified[$j][0]);
                  $tweet = $this->removeUsernameHashtag($tweet);
                  $tweet = $this->hapusURL($tweet);
                  $tweet = $this->fungsiHapussimbol($tweet);
                  $tweet = $stemmer->stem($tweet);
        $term_data_test = explode(" ", $tweet);
        $jumlah_term_uji = count($term_data_test);

        //prior probability
        $probabilitas_valid = $jumlah_valid/$jumlah_tweet;
        $probabilitas_spam = $jumlah_spam/$jumlah_tweet;
        

        for ($i=0; $i < $jumlah_term_uji; $i++) { 
            //mengecek apakah term data uji ada di token hasil training, sekaligus mengetahui indeksnya 
            $index_vocab = array_search($term_data_test[$i], $vocab);
            
            //kalau term data uji ada di token hasil training, maka probabilitas term di hasil training digunakan
            //kalau term data uji tidak ada di token hasil training, maka term data uji diabaikan
            if ($index_vocab != null) {
                $probabilitas_valid = $probabilitas_valid * $hasil_training[$index_vocab-1]['prob_valid'];
                $probabilitas_spam = $probabilitas_spam * $hasil_training[$index_vocab-1]['prob_spam'];
            }
           
        }

        //menentukan kelas data uji
        if($probabilitas_spam > $probabilitas_valid){
            $kelas_sistem = "spam" ;
            
        }elseif ($probabilitas_valid > $probabilitas_spam) {
            $kelas_sistem = "valid" ;

        }elseif ($probabilitas_valid == $probabilitas_spam) {
            $kelas_sistem = "bebas" ;
        }

        if ($tweet_unclassified[$j][1]==1) {
            $kelas_asli = "valid";
        }else{
            $kelas_asli = "spam";
        }

        //menyimpan hasil klasifikasi
        $hasil_klasifikasi[$j]['tweet'] = $tweet_unclassified[$j][0];
        $hasil_klasifikasi[$j]['kelas_asli'] = $kelas_asli;
        $hasil_klasifikasi[$j]['kelas_sistem'] = $kelas_sistem;
        $hasil_klasifikasi[$j]['prob_valid'] = $probabilitas_valid;
        $hasil_klasifikasi[$j]['prob_spam'] = $probabilitas_spam;
        }

        //menghitung hasil klasifikasi benar
        $klasifikasi_true = 0;
        for ($i=0; $i < $jumlah_data_uji ; $i++) { 
            if ($hasil_klasifikasi[$i]['kelas_asli']==$hasil_klasifikasi[$i]['kelas_sistem']) {
                $klasifikasi_true = $klasifikasi_true +1;
            }
        }
        //menghitung akurasi dan false classified
        $akurasi = $klasifikasi_true / $jumlah_data_uji;
        $klasifikasi_false = $jumlah_data_uji - $klasifikasi_true;



        return View::make('klasifikasi.ujimultinomial')->with('hasil_klasifikasi',$hasil_klasifikasi)
            ->with('akurasi',$akurasi)
            ->with('klasifikasi_true',$klasifikasi_true)
            ->with('klasifikasi_false',$klasifikasi_false);
    }

    #melakukan klasifikasi dengan metode bernoulli untuk banyak data
    public function ujiBernoulli(){
        $stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();//load library stemmer sastrawi
        $stemmer  = $stemmerFactory->createStemmer();//load library stemmer sastrawi

        $hasil_training = $this->fungsiBernoulli();//memanggil term hasil training
        //data tes
         $tweet_unclassified = array(array("Jalan, rusak  23232 http://bit.ly/derta di malioboro, tolong segera diperbaiki",1),
                                    array("cewek seksi pamer susu",2),
                                    array("cewek seksi video vulgar",1),
                                    array("ada jembatan jalan rusak di ugm",1),
                                    array("bantu promoto jalan cewek seksi ini guys",2),
                                    array("jalan jalan banyak yang merusak capek deh",1)
                                    );
        //menghitung jumlah data uji
        $jumlah_data_uji = count($tweet_unclassified); 

        
        //menghitung jumlah token dari hasil training
        $jumlah_token_training = count($hasil_training);
        
        

        //menentukan prior 
        $jumlah_spam = Datatraining::where('kelas','=',2) //menghitung tweet spam
                ->count('id');
        $jumlah_valid = Datatraining::where('kelas','=',1) //menghitung tweet valid
                ->count('id'); 
        $jumlah_tweet = Datatraining::count('id');//menghitung jumlah tweet   


        //perulangan klasifikasi bernoulli untuk masing-masing data uji
        for ($j=0; $j < $jumlah_data_uji ; $j++) { 
        //praproses tweet
        $tweet = strtolower($tweet_unclassified[$j][0]);
                  $tweet = $this->removeUsernameHashtag($tweet);
                  $tweet = $this->hapusURL($tweet);
                  $tweet = $this->fungsiHapussimbol($tweet);
                  $tweet = $stemmer->stem($tweet);
        
         //memecah data uji tweet menjadi token
        $term_data_test = explode(" ", $tweet);
        
        //merapikan token
        $term_data_test = $this->merapikan_token($term_data_test);

        //prior probability
        $probabilitas_valid = $jumlah_valid/$jumlah_tweet;
        $probabilitas_spam = $jumlah_spam/$jumlah_tweet;

        //mengecek untuk masing-masing token pada hasil training
        for ($i=0; $i < $jumlah_token_training; $i++) { 
            //mengecek apakah token di hasil training terdapat pada data uji
            $check_in_data = in_array($hasil_training[$i]['term'],$term_data_test);    
            //jika ada, maka probabilitas token tersebut digunakan sebagai pengali
            if ($check_in_data == true)  {
                $pengali_valid = $hasil_training[$i]['prob_valid'];
                $pengali_spam = $hasil_training[$i]['prob_spam'];
            //jika tidak ada, maka (1 - probabilitas token tersebut) digunakan sebagai pengali
            }elseif ($check_in_data == false) {
                $pengali_valid = 1-$hasil_training[$i]['prob_valid'];
                $pengali_spam = 1-$hasil_training[$i]['prob_spam'];
            }
            #untuk mengkoreksi pengali
            // array_push($pengali_baru,$pengali_spam);

            //menghitung probabilitas data uji terhadap kelas valid
            $probabilitas_valid = $probabilitas_valid * $pengali_valid;
            $probabilitas_spam = $probabilitas_spam * $pengali_spam;
        }
        
        // menentukan kelas
        if($probabilitas_spam > $probabilitas_valid){
            $kelas_sistem = "spam" ;
        }elseif ($probabilitas_valid > $probabilitas_spam) {
            $kelas_sistem = "valid" ;
        }

        if ($tweet_unclassified[$j][1]==1) {
            $kelas_asli = "valid";
        }else{
            $kelas_asli = "spam";
        }
         //menyimpan hasil klasifikasi
        $hasil_klasifikasi[$j]['tweet'] = $tweet_unclassified[$j][0];
        $hasil_klasifikasi[$j]['kelas_asli'] = $kelas_asli;
        $hasil_klasifikasi[$j]['kelas_sistem'] = $kelas_sistem;
        $hasil_klasifikasi[$j]['prob_valid'] = $probabilitas_valid;
        $hasil_klasifikasi[$j]['prob_spam'] = $probabilitas_spam;
        }
        //menghitung true classified
        $klasifikasi_true = 0;
        for ($i=0; $i < $jumlah_data_uji ; $i++) { 
            if ($hasil_klasifikasi[$i]['kelas_asli']==$hasil_klasifikasi[$i]['kelas_sistem']) {
                $klasifikasi_true = $klasifikasi_true +1;
            }
        }
        
        //menghitung akurasi dan false classified
        $akurasi = $klasifikasi_true / $jumlah_data_uji;
        $klasifikasi_false = $jumlah_data_uji - $klasifikasi_true;
          
        return View::make('klasifikasi.ujibernoulli')->with('hasil_klasifikasi',$hasil_klasifikasi)
            ->with('klasifikasi_true',$klasifikasi_true)
            ->with('klasifikasi_false',$klasifikasi_false)
            ->with('akurasi',$akurasi);
    }

    #fungsi untuk melakukan training multinomial 

    public function fungsiKlasifikasiMultinomial($hasil_training,$data_uji){
        $stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();//load library stemmer sastrawi
        $stemmer  = $stemmerFactory->createStemmer();//load library stemmer sastrawi
         //memecah data uji dan praproses
        $term_data_test = explode(" ", $stemmer->stem(strtolower($data_uji)));
        $jumlah_term_uji = count($term_data_test);
        
        //membuat array untuk menyimpan token hasil training saja 
        $jumlah_token_training = count($hasil_training);
        for ($i=1; $i <= $jumlah_token_training ; $i++) { 
            $vocab[$i] = $hasil_training[$i-1]['term'];
        }
        
        $probabilitas_valid = 1;
        $probabilitas_spam = 1;
        
        for ($i=0; $i < $jumlah_term_uji; $i++) { 
            //mengecek apakah term data uji ada di token hasil training, sekaligus mengetahui indeksnya 
            $index_vocab = array_search($term_data_test[$i], $vocab);
            
            //kalau term data uji ada di token hasil training, maka probabilitas term di hasil training digunakan
            //kalau term data uji tidak ada di token hasil training, maka term data uji diabaikan
            if ($index_vocab != null) {
                $probabilitas_valid = $probabilitas_valid * $hasil_training[$index_vocab-1]['prob_valid'];
                $probabilitas_spam = $probabilitas_spam * $hasil_training[$index_vocab-1]['prob_spam'];
            }
           
        }

        //menentukan kelas data uji
        if($probabilitas_spam > $probabilitas_valid){
            $kelas = "spam" ;
        }elseif ($probabilitas_valid > $probabilitas_spam) {
            $kelas = "valid" ;
        }
        return $kelas;
    }   
    public function getXYZ(){
        return array(4,5,6);

    }

    public function cobaXYZ(){
        //membuat fungsi mengembalikan multivalue
         list($x,$y,$z) = $this->getXYZ();
         return $z;
    }

    //menampilkan hasil seleksi fitur
    public function showseleksi(){
         //memanggil token hasil praproses
         

         //load token hasil praproses
         $fitur_praproses = FiturPraproses::get();
         $i=0;
         foreach ($fitur_praproses as $row) {
           $token[$i] = $row->term;
           $i++;
         } 

         //1.menampilkan data term hasil seleksi
         $fitur_seleksi = FiturSeleksi::get();
         $i=0;
         foreach ($fitur_seleksi as $row) {
           $token_fix[$i]= $row->term;
           $i++;
         }

         return dd($token_fix);
          return View::make('seleksi.show_hasil')->with('token',$token)
                                                ->with('token_fix',$token_fix);
    }

    //melakukan proses seleksi fitur
    public function cobaseleksi(){
         //memanggil token hasil praproses
         
        //menghapus fitur seleksi 
        $jumlah_fitur= FiturSeleksi2::count('id');
        
        if ($jumlah_fitur != 0) {
          $hapus_fitur = FiturSeleksi2::truncate();
        }


        

         //load token hasil praproses
         $fitur_praproses = FiturPraproses2::get();
         $i=0;
         foreach ($fitur_praproses as $row) {
           $token[$i] = $row->term;
           $i++;
         } 

        //load token hasil praproses dari file .txt
         // $token = unserialize(file_get_contents('token4.txt'));
         

         //2.melakukan proses seleksi 
         //memanggil fungsi untuk seleksi fitur
         //dengan list bisa mengembalikan dua nilai sekaligus
         list($token_matematis,$token_fix) = $this->fungsiSeleksi($token);
         
         $jumlah_token = count($token_fix);
         
         // input ke database
         for ($j=0; $j < $jumlah_token; $j++) { 
             $token_seleksi = new FiturSeleksi2;
             $token_seleksi->term = $token_fix[$j];
             $token_seleksi->save();
         }
         
         file_put_contents("fiturseleksi3.txt", serialize($token_fix));

         return View::make('seleksi.chi_square')->with('token',$token)
                                                ->with('token_fix',$token_fix)
                                                ->with('token_matematis',$token_matematis);
    }

    public function fungsiSeleksi($token){
        // return View::make('skripsi.chi_square');
         $stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();//load library stemmer sastrawi
         $stemmer  = $stemmerFactory->createStemmer();//load library stemmer sastrawi

         // $jumlah_spam = Datatraining::where('kelas','=',2) //menghitung tweet spam
         //        ->count('id');
         // $jumlah_valid = Datatraining::where('kelas','=',1) //menghitung tweet valid
         //        ->count('id'); 
         $jumlah_tweet = Datatraining2::count('id');//menghitung jumlah tweet    

         $data_valids = Datatraining2::where('kelas','=',1)->get(); //meretrieve tweet valid
         
         $data_spams = Datatraining2::where('kelas','=',2)->get(); //meretrieve tweet spam

         $jumlah_tweet = Datatraining2::count('id');//menghitung jumlah tweet 
         $jumlah_valid = Datatraining2::where('kelas','=',1)->count('id'); //meretrieve tweet spam
         $jumlah_spam = Datatraining2::where('kelas','=',2)->count('id'); //meretrieve tweet spam
    
         //menghitung jumlah token hasil praproses
         $jumlah_token = count($token);
         //mendefinisikan array baru

         $a = 0;
         foreach ($data_valids as $row) {
            $tweet_valid[$a] = strtolower($stemmer->stem($row->tweet));
            $a = $a+1;
         }
         $b=0;
         foreach ($data_spams as $row) {
            $tweet_spam[$b] = strtolower($stemmer->stem($row->tweet));
            $b = $b+1;
         }
         
       
         $token_fix = array();
         // return dd($token);
         for ($i=0; $i < $jumlah_token; $i++) { 
             $kata = $token[$i];
             $jumlah_A = 0;
             $jumlah_B = 0;
             //menghitung jumlah tweet valid yang mengandung token ke-i


             for ($j=0; $j < $jumlah_valid ; $j++) { 
                $cek_kemunculan = "/\b$kata\b/";
                
                if(preg_match($cek_kemunculan, $tweet_valid[$j])) {
                    $jumlah_A = $jumlah_A+1;
                }
             }

             for ($j=0; $j < $jumlah_spam ; $j++) { 
                $cek_kemunculan = "/\b$kata\b/";
                
                if(preg_match($cek_kemunculan, $tweet_spam[$j])) {
                    $jumlah_B = $jumlah_B+1;
                }
             }
             // foreach ($data_valids as $row) {
             //    $tweet = strtolower($stemmer->stem($row->tweet));
             //    //cara 3
                
             //    $cek_kemunculan = "/\b$kata\b/";
                
             //    if(preg_match($cek_kemunculan, $tweet)) {
             //        $jumlah_A = $jumlah_A+1;
             //    }

             //    //cara 1

             //    // $term = explode(" ",$tweet);
             //    // $jumlah_term = count($term);
             //    // for ($k=0; $k < $jumlah_term; $k++) { 
             //    //   if ($term[$k] == $kata) {
             //    //      $jumlah_A= $jumlah_A +1;
             //    //   }
             //    // }

             //    //cara 2 

             //    // if (strpos(strtolower($stemmer->stem($row->tweet)), $kata) !== false) {
             //    // $jumlah_A = $jumlah_A + 1;
             //    // }

                

             // }
             //menghitung jumlah tweet spam yang mengandung token ke-i
             // foreach ($data_spams as $row) {
             //    $tweet = strtolower($stemmer->stem($row->tweet));
               
             //    //cara 3
             //    $cek_kemunculan = "/\b$kata\b/";
                
             //    if(preg_match($cek_kemunculan, $tweet)) {
             //        $jumlah_B = $jumlah_B+1;
             //    }
             //    //cara 1

             //    // $term = explode(" ",$tweet);
             //    // $jumlah_term = count($term);
             //    // for ($k=0; $k < $jumlah_term; $k++) { 
             //    //   if ($term[$k] == $kata) {
             //    //      $jumlah_B= $jumlah_B +1;
             //    //   }
             //    // }
                
             //    //cara 2

             //    // if (strtolower($stemmer->stem($row->tweet)) == $kata) ) {
             //    // $jumlah_B = $jumlah_B + 1;
             //    // }
             
             #mencari jumlah dokumen pada kelas valid yang tidak mengandung term X
             $jumlah_C = $jumlah_valid-$jumlah_A;
             #mencari jumlah dokumen pada kelas spam yang tidak mengandung term X
             $jumlah_D = $jumlah_spam-$jumlah_B;

             $penyebut = ($jumlah_A + $jumlah_B) * ( $jumlah_C+$jumlah_D )* ($jumlah_A+$jumlah_C) * ($jumlah_B+$jumlah_D); //mencari penyebut 
             if($penyebut ==0){
                $nilai_chi = 0;
             }else{
             $pembilang_kanan = (($jumlah_A*$jumlah_D)-($jumlah_B*$jumlah_C)); //mencari pembilang bagian kanan untuk persamaan mencari nilai chi square 
             $nilai_chi = $jumlah_tweet * pow($pembilang_kanan,2) / $penyebut;  //menghitung nilai chi square
            }
             //menyimpan nilai A,B,C,D, Chi Square dan term ke dalam array
             $token_seleksi[$i]['token'] = $kata;
             $token_seleksi[$i]['jumlah_A'] = $jumlah_A;
             $token_seleksi[$i]['jumlah_B'] = $jumlah_B;
             $token_seleksi[$i]['jumlah_C'] = $jumlah_C;
             $token_seleksi[$i]['jumlah_D'] = $jumlah_D;
             $token_seleksi[$i]['nilai_chi'] = $nilai_chi;

             //jika nilai chi square memenuhi treshold masukkan ke array token_baru
            
             
             if ($token_seleksi[$i]['nilai_chi']>10.83) {
                 $token_fix[$i] = $kata ;
                 // $token_baru[$i]['nilai_chi'] = $token_seleksi[$i]['nilai_chi'];
             }
         }
         
         //merapikan token baru hasil seleksi
         
         $token_fix = $this->merapikan_token($token_fix);

        
         //mengembalikan dua nilai dengan fungsi, yakni token matematis dan token baru
         return array($token_seleksi,$token_fix);

    }


    public function fungsiMultinomial(){
         $stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();//load library stemmer sastrawi
         $stemmer  = $stemmerFactory->createStemmer();//load library stemmer sastrawi
        //cara lambat
         $jumlah_spam = Datatraining::where('kelas','=',2) //menghitung tweet spam
                ->count('id');
         $jumlah_valid = Datatraining::where('kelas','=',1) //menghitung tweet valid
                ->count('id'); 
         $jumlah_tweet = Datatraining::count('id');//menghitung jumlah tweet    

         $data_valids = Datatraining::where('kelas','=',1)->get(); //meretrieve tweet valid
         $data_spams = Datatraining::where('kelas','=',2)->get(); //meretrieve tweet spam
         $data_tweets = Datatraining::get(); //meretrieve seluruh tweet
         
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

         return $hasil_training;        
    }

    #fungsi untuk melakukan training bernoulli
    public function fungsiBernoulli(){
        $stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();//load library stemmer sastrawi
         $stemmer  = $stemmerFactory->createStemmer();//load library stemmer sastrawi
         
         $data_tweets = Datatraining::get(); //meretrieve seluruh tweet
         $tweets_valid = Datatraining::where('kelas','=',1)->get();
         $tweets_spam = Datatraining::where('kelas','=',2)->get();

         $jumlah_tweet_valid = Datatraining::where('kelas','=',1)->count('id');
         $jumlah_tweet_spam = Datatraining::where('kelas','=',2)->count('id');
         
         $token = $this->praproses();
         list($token_matematis,$token) = $this->fungsiSeleksi($token);


         $jumlah_token = count($token);
         $hasil_training = array();

         for ($i=0; $i < $jumlah_token; $i++) { 
              $happened_valid = 0;
              $happened_spam = 0;


              foreach ($tweets_valid as $row) {
                   //praproses tweet
                  $tweet = strtolower($row->tweet);
                  $tweet = $this->removeUsernameHashtag($tweet);
                  $tweet = $this->hapusURL($tweet);
                  $tweet = $this->fungsiHapussimbol($tweet);
                  $tweet = $stemmer->stem($tweet);

                  if (strpos($tweet,$token[$i]) !== false) { // mengecek tweet pada kelas valid mengandung term x tidak
                     $happened_valid = $happened_valid+1; // jika mengandung maka jumlah kelas c yang mengandung x ditambah 1
                  }
              }

              foreach ($tweets_spam as $row) {
                  //praproses tweet
                  $tweet = strtolower($row->tweet);
                  $tweet = $this->removeUsernameHashtag($tweet);
                  $tweet = $this->hapusURL($tweet);
                  $tweet = $this->fungsiHapussimbol($tweet);
                  $tweet = $stemmer->stem($tweet);

                  if (strpos($tweet,$token[$i]) !== false) { // mengecek tweet pada kelas valid mengandung term x tidak
                     $happened_spam = $happened_spam+1; // jika mengandung maka jumlah kelas c yang mengandung x ditambah 1
                  }
              }
            

              //menghitung probabilitas term terhadap kelas valid
              $prob_term_valid = ($happened_valid + 1)/($jumlah_tweet_valid + 2);

              //menghitung probabilitas term terhadap kelas spam
              $prob_term_spam = ($happened_spam + 1)/($jumlah_tweet_spam + 2);
              
              $hasil_training[$i]['term']=$token[$i];
              $hasil_training[$i]['happened_valid']=$happened_valid;
              $hasil_training[$i]['happened_spam']=$happened_spam;
              $hasil_training[$i]['prob_valid']=round($prob_term_valid,3);
              $hasil_training[$i]['prob_spam']=round($prob_term_spam,3);
              
              
         }
         return $hasil_training;
    }
    #proses menampilkan hasil training bernoulli
    public function trainingBernoulli(){
        $hasil_training = $this->fungsiBernoulli();
        return View::make('training.bernoulli')->with('hasil_training',$hasil_training); 
    }
    #proses menampilkan hasil training multinomial
    public function trainingMultinomial(){
        $hasil_training = $this->fungsiMultinomial();
        return View::make('training.multinomial')->with('hasil_training',$hasil_training); 
    }
    public function getHasilseleksi(){
         
         return View::make('skripsi.hasilseleksi'); 
    }
    public function getHasiltraining(){
         
         return View::make('skripsi.hasil-training'); 
    }
    public function getKlasifikasi(){
         
         return View::make('skripsi.klasifikasi'); 
    }
    public function getBernoulli(){
         
         return View::make('skripsi.bernoulli'); 
    }

    #EVALUASI

    public function getPengaruhClassifier(){
         
         return View::make('evaluasi.pengaruhclassifier'); 
    }
    public function getPengaruhSeleksi(){
         
         return View::make('evaluasi.pengaruhseleksi'); 
    }
    public function getPengaruhSkalabilitas(){
         
         return View::make('evaluasi.pengaruhskalabilitas'); 
    }
    public function getChart(){
         
         return View::make('skripsi.chart'); 
    }



    

  
	

      

}


	