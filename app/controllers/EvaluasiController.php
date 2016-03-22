<?php

class EvaluasiController extends BaseController {
    
    public function pengaruhclassifier(){
        list($akurasi_m,$precision_m,$recall_m,$f_measure_m) = $this->UjiMultinomial(1);
        list($akurasi_b,$precision_b,$recall_b,$f_measure_b) = $this->UjiBernoulli(1);
       
        
        return View::make('evaluasi.pengaruhclassifier')
            ->with('akurasi_m',$akurasi_m)
            ->with('akurasi_b',$akurasi_b)
            ->with('precision_m',$precision_m)
            ->with('precision_b',$precision_b)
            ->with('recall_m',$recall_m)
            ->with('recall_b',$recall_b)
            ->with('f_measure_m',$f_measure_m)
            ->with('f_measure_b',$f_measure_b);            ; 
    } 

    public function pengaruhseleksi(){
        list($akurasi_m,$precision_m,$recall_m,$f_measure_m) = $this->UjiMultinomial(1);
        list($akurasi_b,$precision_b,$recall_b,$f_measure_b) = $this->UjiBernoulli(1);
        list($akurasi_m_s,$precision_m_s,$recall_m_s,$f_measure_m_s) = $this->UjiMultinomial(2);
        list($akurasi_b_s,$precision_b_s,$recall_b_s,$f_measure_b_s) = $this->UjiBernoulli(2);
        

        
        return View::make('evaluasi.pengaruhseleksi')
            ->with('akurasi_m',$akurasi_m)
            ->with('akurasi_b',$akurasi_b)
            ->with('precision_m',$precision_m)
            ->with('precision_b',$precision_b)
            ->with('recall_m',$recall_m)
            ->with('recall_b',$recall_b)
            ->with('f_measure_m',$f_measure_m)
            ->with('f_measure_b',$f_measure_b)
            ->with('akurasi_m_s',$akurasi_m_s)
            ->with('akurasi_b_s',$akurasi_b_s)
            ->with('precision_m_s',$precision_m_s)
            ->with('precision_b_s',$precision_b_s)
            ->with('recall_m_s',$recall_m_s)
            ->with('recall_b_s',$recall_b_s)
            ->with('f_measure_m_s',$f_measure_m_s)
            ->with('f_measure_b_s',$f_measure_b_s);        
    } 

    public function pengaruhDataTraining(){

        //1. Fitur Praproses D1
        //2. Fitur Seleksi D1
        //3. Fitur Praproses D2
        //4. Fitur Seleksi D2
        list($akurasi_m_1,$precision_m_1,$recall_m_1,$f_measure_m_1) = $this->UjiMultinomial(2);
        list($akurasi_m_2,$precision_m_2,$recall_m_2,$f_measure_m_2) = $this->UjiMultinomial(4);
        list($akurasi_b_1,$precision_b_1,$recall_b_1,$f_measure_b_1) = $this->UjiBernoulli(2);
        list($akurasi_b_2,$precision_b_2,$recall_b_2,$f_measure_b_2) = $this->UjiBernoulli(4);

       
        
        return View::make('evaluasi.pengaruhdatatraining')
            ->with('akurasi_m_1',$akurasi_m_1)
            ->with('akurasi_m_2',$akurasi_m_2)
            ->with('precision_m_1',$precision_m_1)
            ->with('precision_m_2',$precision_m_2)
            ->with('recall_m_1',$recall_m_1)
            ->with('recall_m_2',$recall_m_2)
            ->with('f_measure_m_1',$f_measure_m_1)
            ->with('f_measure_m_2',$f_measure_m_2)
            ->with('akurasi_b_1',$akurasi_b_1)
            ->with('akurasi_b_2',$akurasi_b_2)
            ->with('precision_b_1',$precision_b_1)
            ->with('precision_b_2',$precision_b_2)
            ->with('recall_b_1',$recall_b_1)
            ->with('recall_b_2',$recall_b_2)
            ->with('f_measure_b_1',$f_measure_b_1)
            ->with('f_measure_b_2',$f_measure_b_2);         
    }



    #melakukan klasifikasi dengan metode bernoulli untuk banyak data
    public function ujiBernoulli($jenis){
        $stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();//load library stemmer sastrawi
        $stemmer  = $stemmerFactory->createStemmer();//load library stemmer sastrawi

        //memanggil term hasil training bernoulli

        if ($jenis ==1) {
            $hasil_training = FiturPraproses::get();
        }elseif ($jenis ==2){
            $hasil_training = FiturSeleksi::get();
        }elseif ($jenis ==3){
            $hasil_training = FiturPraproses2::get();
        }elseif ($jenis ==4){
            $hasil_training = FiturSeleksi2::get();
        }
        
        $i=0;
        foreach ($hasil_training as $row) {
                $hasil_training[$i]['term'] = $row->term;
                $hasil_training[$i]['prob_valid'] = $row->prob_valid_b;
                $hasil_training[$i]['prob_spam'] = $row->prob_spam_b;
                $i++;
        }



        //memanggil data uji
        $data_uji = Datauji::get();
        $i = 0;
        $data_valid = 0;
        foreach ($data_uji as $row) {
            $tweet_unclassified[$i][0] = $row->tweet;
            $tweet_unclassified[$i][1] = $row->kelas_asli;
            $i++;
            if ($row->kelas_asli == 1) {
                $data_valid = $data_valid +1;
            }
        }
        
        
        //menghitung jumlah data uji
        $jumlah_data_uji = count($tweet_unclassified); 

        
        //menghitung jumlah token dari hasil training
        $jumlah_token_training = count($hasil_training);
        
        if ($jenis ==1) {
            //menentukan prior 
            $jumlah_spam = Datatraining::where('kelas','=',2) //menghitung tweet spam
                ->count('id');
            $jumlah_valid = Datatraining::where('kelas','=',1) //menghitung tweet valid
                ->count('id'); 
            $jumlah_tweet = Datatraining::count('id');//menghitung jumlah tweet   
        }elseif ($jenis ==2){
            //menentukan prior 
            $jumlah_spam = Datatraining::where('kelas','=',2) //menghitung tweet spam
                ->count('id');
            $jumlah_valid = Datatraining::where('kelas','=',1) //menghitung tweet valid
                ->count('id'); 
            $jumlah_tweet = Datatraining::count('id');//menghitung jumlah tweet   
        }elseif ($jenis ==3){
            //menentukan prior 
            $jumlah_spam = Datatraining2::where('kelas','=',2) //menghitung tweet spam
                ->count('id');
            $jumlah_valid = Datatraining2::where('kelas','=',1) //menghitung tweet valid
                ->count('id'); 
            $jumlah_tweet = Datatraining2::count('id');//menghitung jumlah tweet   
        }elseif ($jenis ==4){
            //menentukan prior 
            $jumlah_spam = Datatraining2::where('kelas','=',2) //menghitung tweet spam
                ->count('id');
            $jumlah_valid = Datatraining2::where('kelas','=',1) //menghitung tweet valid
                ->count('id'); 
            $jumlah_tweet = Datatraining2::count('id');//menghitung jumlah tweet   
        }

        

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
        
       //evaluasi klasifikasi
        $true_positives = 0;
        $false_positives = 0;
        $false_negatives = 0;
        $true_negatives = 0;
        for ($i=0; $i < $jumlah_data_uji ; $i++) { 
            if (($hasil_klasifikasi[$i]['kelas_asli']=="spam") && ($hasil_klasifikasi[$i]['kelas_sistem']=="spam")) {
                $true_positives = $true_positives +1;
            }
        }

        for ($j=0; $j < $jumlah_data_uji ; $j++) { 
            if ($hasil_klasifikasi[$j]['kelas_asli']=="spam" && $hasil_klasifikasi[$j]['kelas_sistem']=="valid") {
                $false_positives = $false_positives +1;
            }
        }

        for ($i=0; $i < $jumlah_data_uji ; $i++) { 
            if ($hasil_klasifikasi[$i]['kelas_asli']=="valid" && $hasil_klasifikasi[$i]['kelas_sistem']=="spam") {
                $false_negatives = $false_negatives +1;
            }
        }

        for ($i=0; $i < $jumlah_data_uji ; $i++) { 
            if ($hasil_klasifikasi[$i]['kelas_asli']=="valid" && $hasil_klasifikasi[$i]['kelas_sistem']=="valid") {
                $true_negatives = $true_negatives +1;
            }
        }
        

        //menghitung akurasi dan false classified
        $akurasi_b = ($true_positives+$true_negatives)/ $jumlah_data_uji;
        $precision_b = $true_positives / ( $true_positives + $false_positives);
        $recall_b = $true_positives / ( $true_positives + $false_negatives);
        $f_measure_b = 2*$precision_b*$recall_b/($precision_b+$recall_b);


        return array($akurasi_b,$precision_b,$recall_b,$f_measure_b);
        
          
        
    }


    #proses uji klasifikasi dengan multinomial
    public function UjiMultinomial($jenis){
      
        $stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();//load library stemmer sastrawi
        $stemmer  = $stemmerFactory->createStemmer();//load library stemmer sastrawi

        $jumlah_spam = Datauji::where('kelas_asli','=',2)->count('id');
       
        //memanggil term hasil training multinomial
        if ($jenis ==1) {
            $hasil_training = FiturPraproses::get();
        }elseif ($jenis ==2){
            $hasil_training = FiturSeleksi::get();
        }elseif ($jenis ==3){
            $hasil_training = FiturPraproses2::get();
        }elseif ($jenis ==4){
            $hasil_training = FiturSeleksi2::get();
        }
        
        $i=0;
        foreach ($hasil_training as $row) {
                $hasil_training[$i]['term'] = $row->term;
                $hasil_training[$i]['prob_valid'] = $row->prob_valid_m;
                $hasil_training[$i]['prob_spam'] = $row->prob_spam_m;
                $i++;
        }
        

        $data_uji = Datauji::get();
        $i = 0;
        foreach ($data_uji as $row) {
            $tweet_unclassified[$i][0] = $row->tweet;
            $tweet_unclassified[$i][1] = $row->kelas_asli;
            $i++;
        }
        // return dd($tweet_unclassified);
        
        //membuat array untuk menyimpan token hasil training saja 
        $jumlah_token_training = count($hasil_training);
        for ($i=1; $i <= $jumlah_token_training ; $i++) { 
            $vocab[$i] = $hasil_training[$i-1]['term'];
        }

         if ($jenis ==1) {
            //menentukan prior 
            $jumlah_spam = Datatraining::where('kelas','=',2) //menghitung tweet spam
                ->count('id');
            $jumlah_valid = Datatraining::where('kelas','=',1) //menghitung tweet valid
                ->count('id'); 
            $jumlah_tweet = Datatraining::count('id');//menghitung jumlah tweet   
        }elseif ($jenis ==2){
            //menentukan prior 
            $jumlah_spam = Datatraining::where('kelas','=',2) //menghitung tweet spam
                ->count('id');
            $jumlah_valid = Datatraining::where('kelas','=',1) //menghitung tweet valid
                ->count('id'); 
            $jumlah_tweet = Datatraining::count('id');//menghitung jumlah tweet   
        }elseif ($jenis ==3){
            //menentukan prior 
            $jumlah_spam = Datatraining2::where('kelas','=',2) //menghitung tweet spam
                ->count('id');
            $jumlah_valid = Datatraining2::where('kelas','=',1) //menghitung tweet valid
                ->count('id'); 
            $jumlah_tweet = Datatraining2::count('id');//menghitung jumlah tweet   
        }elseif ($jenis ==4){
            //menentukan prior 
            $jumlah_spam = Datatraining2::where('kelas','=',2) //menghitung tweet spam
                ->count('id');
            $jumlah_valid = Datatraining2::where('kelas','=',1) //menghitung tweet valid
                ->count('id'); 
            $jumlah_tweet = Datatraining2::count('id');//menghitung jumlah tweet   
        }
        
        

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

        //evaluasi klasifikasi
        $true_positives = 0;
        $false_positives = 0;
        $false_negatives = 0;
        $true_negatives = 0;
        for ($i=0; $i < $jumlah_data_uji ; $i++) { 
            if (($hasil_klasifikasi[$i]['kelas_asli']=="spam") && ($hasil_klasifikasi[$i]['kelas_sistem']=="spam")) {
                $true_positives = $true_positives +1;
            }
        }

        for ($j=0; $j < $jumlah_data_uji ; $j++) { 
            if ($hasil_klasifikasi[$j]['kelas_asli']=="spam" && $hasil_klasifikasi[$j]['kelas_sistem']=="valid") {
                $false_positives = $false_positives +1;
            }
        }

        for ($i=0; $i < $jumlah_data_uji ; $i++) { 
            if ($hasil_klasifikasi[$i]['kelas_asli']=="valid" && $hasil_klasifikasi[$i]['kelas_sistem']=="spam") {
                $false_negatives = $false_negatives +1;
            }
        }

        for ($i=0; $i < $jumlah_data_uji ; $i++) { 
            if ($hasil_klasifikasi[$i]['kelas_asli']=="valid" && $hasil_klasifikasi[$i]['kelas_sistem']=="valid") {
                $true_negatives = $true_negatives +1;
            }
        }
        

        //menghitung akurasi dan false classified
        $akurasi = ($true_positives+$true_negatives)/ $jumlah_data_uji;
        $precision = $true_positives / ( $true_positives + $false_positives);
        $recall = $true_positives / ( $true_positives + $false_negatives);
        $f_measure = 2*$precision*$recall/($precision+$recall);

        return array($akurasi,$precision,$recall,$f_measure);

        
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


    