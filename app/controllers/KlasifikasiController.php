<?php

class KlasifikasiController extends BaseController {

	#proses mengklasifikasikan satu data uji dengan multinomial

    public function ShowTestFromUser(){
        return View::make('klasifikasi.testing');
    }

    public function ResultTestFromUser(){
        $stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();//load library stemmer sastrawi
        $stemmer  = $stemmerFactory->createStemmer();//load library stemmer sastrawi
        $tweet = Input::get('tweet');
        $tweet_asli = $tweet;
        //praproses
        $tweet = $this->removeUsernameHashtag($tweet);
        $tweet = $this->hapusURL($tweet);
        $tweet = $this->fungsiHapussimbol($tweet);
        $tweet = $this->normalisasiKata($tweet);
        $tweet = $this->removeCommonWords($tweet);
        $tweet = $stemmer->stem($tweet);
        $tweet_praproses = $tweet;
        
        //$fungsiMultinomialSatu($tweet,$jenis_data)
        //1. Praproses (D1)
        //2. Seleksi (D1)
        //3. Praproses (D2)
        //4. Seleksi (D2)

        #ndak kepake
        // list($kelas_m_1,$probabilitas_valid_m_1,$probabilitas_spam_m_1) = $this->fungsiMultinomialSatu($tweet,1);
        // list($kelas_m_2,$probabilitas_valid_m_2,$probabilitas_spam_m_2) = $this->fungsiMultinomialSatu($tweet,3);
        // list($kelas_b_1,$probabilitas_valid_b_1,$probabilitas_spam_b_1) = $this->fungsiBernoulliSatu($tweet,1);
        // list($kelas_b_2,$probabilitas_valid_b_2,$probabilitas_spam_b_2) = $this->fungsiBernoulliSatu($tweet,3);

        list($kelas_m_s1,$probabilitas_valid_m_s1,$probabilitas_spam_m_s1) = $this->fungsiMultinomialSatu($tweet,2);
        list($kelas_m_s2,$probabilitas_valid_m_s2,$probabilitas_spam_m_s2) = $this->fungsiMultinomialSatu($tweet,4);
        list($kelas_b_s1,$probabilitas_valid_b_s1,$probabilitas_spam_b_s1) = $this->fungsiBernoulliSatu($tweet,2);
        list($kelas_b_s2,$probabilitas_valid_b_s2,$probabilitas_spam_b_s2) = $this->fungsiBernoulliSatu($tweet,4);
        
        return View::make('klasifikasi.hasilklasifikasi')
        ->with('tweet_asli',$tweet_asli)
        ->with('tweet_praproses',$tweet_praproses)
        ->with('kelas_m_s1',$kelas_m_s1)
        ->with('kelas_m_s2',$kelas_m_s2)
        ->with('kelas_b_s1',$kelas_b_s1)
        ->with('kelas_b_s2',$kelas_b_s2)
        ->with('probabilitas_valid_m_s1',$probabilitas_valid_m_s1)
        ->with('probabilitas_valid_m_s2',$probabilitas_valid_m_s2)
        ->with('probabilitas_valid_b_s1',$probabilitas_valid_b_s1)
        ->with('probabilitas_valid_b_s2',$probabilitas_valid_b_s2)
        ->with('probabilitas_spam_m_s1',$probabilitas_spam_m_s1)
        ->with('probabilitas_spam_m_s2',$probabilitas_spam_m_s2) 
        ->with('probabilitas_spam_b_s1',$probabilitas_spam_b_s1)
        ->with('probabilitas_spam_b_s2',$probabilitas_spam_b_s2);

         #ndak kepake
         // ->with('kelas_m_1',$kelas_m_1)
         // ->with('kelas_m_2',$kelas_m_2)
         // ->with('kelas_b_1',$kelas_b_1)
         // ->with('kelas_b_2',$kelas_b_2)
         // ->with('probabilitas_valid_m_1',$probabilitas_valid_m_1)
         // ->with('probabilitas_valid_m_2',$probabilitas_valid_m_2)
         // ->with('probabilitas_valid_b_1',$probabilitas_valid_b_1)
         // ->with('probabilitas_valid_b_2',$probabilitas_valid_b_2)
         // ->with('probabilitas_spam_m_1',$probabilitas_spam_m_1)
         // ->with('probabilitas_spam_m_2',$probabilitas_spam_m_2)
         // ->with('probabilitas_spam_b_1',$probabilitas_spam_b_1)
         // ->with('probabilitas_spam_b_2',$probabilitas_spam_b_2)
    }

    public function SearchFromAPI(){
        return View::make('klasifikasi.searchfromapi');
    }

    public function SearchKeyword(){
        $stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();//load library stemmer sastrawi
        $stemmer  = $stemmerFactory->createStemmer();//load library stemmer sastrawi
        $keyword = Input::get('keyword');
        $keyword = rawurlencode($keyword);

        include(app_path().'/includes/TwitterAPIExchange.php');
        #konfigurasi autentifikasi twitter API
    
        $settings = array(
            'oauth_access_token' => "3228114704-9XbnfYIuMu3FicOSxImQmoqwUjkzposZ576IUnB",
            'oauth_access_token_secret' => "wqUjVbIoxQpzwtBys4C5GYpfGspnasiXVtwpzT89HlSsS",
            'consumer_key' => "4jElwcqJSkYaUE05UHhEPLzUz",
            'consumer_secret' => "HGAdUDoIDFu9SCXZKRaDC4eMWAAfoFp3fA4sD3gF0yHmbibsWF"
        );


    
        $url = "https://api.twitter.com/1.1/search/tweets.json";
        ##method twitter API
        $requestMethod = "GET";
        ##keyword pengambilan data
        $getfield = 'q='.$keyword.'&count=10';



        #eksekusi pengambilan data oleh twitter API
        $twitter = new TwitterAPIExchange($settings);
        $string = json_decode($twitter->setGetfield($getfield)
        ->buildOauth($url, $requestMethod)
        ->performRequest(),$assoc = TRUE);

        //normalisasi format data
        $string = $string['statuses'];

        $i=0;
        foreach ($string as $row) {
            $data_tweet[$i] = $string[$i]['text'];
       
            $i=$i+1;
        }

        $i = rand(0, 9);
        //tweet hasil pencarian random
        $tweet = $data_tweet[$i];

        $tweet_asli = $tweet;
        //praproses
        $tweet = $this->removeUsernameHashtag($tweet);
        $tweet = $this->hapusURL($tweet);
        $tweet = $this->fungsiHapussimbol($tweet);
        $tweet = $this->removeCommonWords($tweet);
        $tweet = $stemmer->stem($tweet);
        $tweet_praproses = $tweet;


        list($kelas_m_s1,$probabilitas_valid_m_s1,$probabilitas_spam_m_s1) = $this->fungsiMultinomialSatu($tweet,2);
        list($kelas_m_s2,$probabilitas_valid_m_s2,$probabilitas_spam_m_s2) = $this->fungsiMultinomialSatu($tweet,4);
        list($kelas_b_s1,$probabilitas_valid_b_s1,$probabilitas_spam_b_s1) = $this->fungsiBernoulliSatu($tweet,2);
        list($kelas_b_s2,$probabilitas_valid_b_s2,$probabilitas_spam_b_s2) = $this->fungsiBernoulliSatu($tweet,4);
        
        return View::make('klasifikasi.hasilklasifikasi')
        ->with('tweet_asli',$tweet_asli)
        ->with('tweet_praproses',$tweet_praproses)
        ->with('kelas_m_s1',$kelas_m_s1)
        ->with('kelas_m_s2',$kelas_m_s2)
        ->with('kelas_b_s1',$kelas_b_s1)
        ->with('kelas_b_s2',$kelas_b_s2)
        ->with('probabilitas_valid_m_s1',$probabilitas_valid_m_s1)
        ->with('probabilitas_valid_m_s2',$probabilitas_valid_m_s2)
        ->with('probabilitas_valid_b_s1',$probabilitas_valid_b_s1)
        ->with('probabilitas_valid_b_s2',$probabilitas_valid_b_s2)
        ->with('probabilitas_spam_m_s1',$probabilitas_spam_m_s1)
        ->with('probabilitas_spam_m_s2',$probabilitas_spam_m_s2) 
        ->with('probabilitas_spam_b_s1',$probabilitas_spam_b_s1)
        ->with('probabilitas_spam_b_s2',$probabilitas_spam_b_s2);
        
    }

    public function ShowTestFromAPI(){
        return View::make('klasifikasi.showtesfromapi');
    }

    public function testfromapi(){

    }

    public function fungsiMultinomialSatu($tweet,$jenis_data){
        $stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();//load library stemmer sastrawi
        $stemmer  = $stemmerFactory->createStemmer();//load library stemmer sastrawi

        // $hasil_training = $this->fungsiMultinomial();//memanggil term hasil training
        if ($jenis_data==1) {
            $hasil_training_praproses = FiturPraproses::get();
        }elseif ($jenis_data==2) {
            $hasil_training_praproses = FiturSeleksi::get();
        }elseif ($jenis_data==3) {
            $hasil_training_praproses = FiturPraproses2::get();
        }elseif ($jenis_data==4) {
            $hasil_training_praproses = FiturSeleksi2::get();
        }
        
        $i=0;
        foreach ($hasil_training_praproses as $row) {
            $hasil_training[$i]['term'] = $row->term;
            $hasil_training[$i]['prob_valid'] = $row->prob_valid_m;
            $hasil_training[$i]['prob_spam'] = $row->prob_spam_m;
            $i++;
        }
         // return dd($hasil_training);


        // praproses tweet uji
        // $tweet = $this->removeUsernameHashtag($tweet);
        // $tweet = $this->hapusURL($tweet);
        // $tweet = $this->fungsiHapussimbol($tweet);
        // $tweet = $stemmer->stem($tweet);
        // return dd($tweet);
         
        //memecah data uji dan praproses
        $term_data_test = explode(" ", $tweet);
        $jumlah_term_uji = count($term_data_test);
        
        //membuat array untuk menyimpan token hasil training saja 
        $jumlah_token_training = count($hasil_training);
        for ($i=1; $i <= $jumlah_token_training ; $i++) { 
            $vocab[$i] = $hasil_training[$i-1]['term'];
        }
        
        
        //menentukan prior berdasarkan jenis data
        if ($jenis_data==1 ) {
            $jumlah_spam = Datatraining::where('kelas','=',2) //menghitung tweet spam
                ->count('id');
            $jumlah_valid = Datatraining::where('kelas','=',1) //menghitung tweet valid
                ->count('id'); 
            $jumlah_tweet = Datatraining::count('id');//menghitung jumlah tweet 
        }elseif ($jenis_data==2) {
            $jumlah_spam = Datatraining::where('kelas','=',2) //menghitung tweet spam
                ->count('id');
            $jumlah_valid = Datatraining::where('kelas','=',1) //menghitung tweet valid
                ->count('id'); 
            $jumlah_tweet = Datatraining::count('id');//menghitung jumlah tweet 
        }elseif ($jenis_data==3) {
            $jumlah_spam = Datatraining2::where('kelas','=',2) //menghitung tweet spam
                ->count('id');
            $jumlah_valid = Datatraining2::where('kelas','=',1) //menghitung tweet valid
                ->count('id'); 
            $jumlah_tweet = Datatraining2::count('id');//menghitung jumlah tweet 
        }elseif ($jenis_data==4) {
            $jumlah_spam = Datatraining2::where('kelas','=',2) //menghitung tweet spam
                ->count('id');
            $jumlah_valid = Datatraining2::where('kelas','=',1) //menghitung tweet valid
                ->count('id'); 
            $jumlah_tweet = Datatraining2::count('id');//menghitung jumlah tweet 
        }
          

        //prior probability
        $probabilitas_spam = $jumlah_spam/$jumlah_tweet;
        $probabilitas_valid = $jumlah_valid/$jumlah_tweet;
       
       // return dd($probabilitas_spam);
        
        $pengali = array();
        for ($i=0; $i < $jumlah_term_uji; $i++) { 
            //mengecek apakah term data uji ada di token hasil training, sekaligus mengetahui indeksnya 
            $index_vocab = array_search($term_data_test[$i], $vocab);
    
           
            //kalau term data uji ada di token hasil training, maka probabilitas term di hasil training digunakan
            //kalau term data uji tidak ada di token hasil training, maka term data uji diabaikan
            if ($index_vocab != false) {

                $probabilitas_valid = $probabilitas_valid * $hasil_training[$index_vocab-1]['prob_valid'];
                $probabilitas_spam = $probabilitas_spam * $hasil_training[$index_vocab-1]['prob_spam'];
               
                //pengali akan dimasukkan ke array $pengali, untuk kroscek saja
                array_push($pengali,$hasil_training[$index_vocab-1]['prob_valid']);
            }else{
                //probabilitas term yang tidak digunakan akan dimasukkan ke array $pengali, untuk kroscek saja
                array_push($pengali,"kosong");
            }
           
        }
        // return dd($pengali);
        


        //menentukan kelas data uji
        if($probabilitas_spam > $probabilitas_valid){
            $kelas = "spam" ;
        }elseif ($probabilitas_valid > $probabilitas_spam) {
            $kelas = "valid" ;
        }else{
            $kelas = "bebas";
        }

        // return dd($probabilitas_valid);
    
        return array($kelas,$probabilitas_valid,$probabilitas_spam);
        //mengembalikan ke view
        // return View::make('klasifikasi.multinomialsatu')
        //     ->with('data_uji',$tweet_unclassified)
        //     ->with('kelas',$kelas)
        //     ->with('probabilitas_valid',$probabilitas_valid)
        //     ->with('probabilitas_spam',$probabilitas_spam)
        //     ->with('hasil_training',$hasil_training)
        //     ->with('tweet',$tweet);
    }

    #proses mengklasifikasikan satu data uji dengan model bernoulli
    public function fungsiBernoulliSatu($tweet,$jenis_data){
        $stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();//load library stemmer sastrawi
        $stemmer  = $stemmerFactory->createStemmer();//load library stemmer sastrawi

        
        //memanggil term hasil training bernoulli
        if ($jenis_data==1) {
            $hasil_training_praproses = FiturPraproses::get();
        }elseif ($jenis_data==2) {
            $hasil_training_praproses = FiturSeleksi::get();
        }elseif ($jenis_data==3) {
            $hasil_training_praproses = FiturPraproses2::get();
        }elseif ($jenis_data==4) {
            $hasil_training_praproses = FiturSeleksi2::get();
        }


        $i=0;
        foreach ($hasil_training_praproses as $row) {
            $hasil_training[$i]['term'] = $row->term;
            $hasil_training[$i]['prob_valid'] = $row->prob_valid_b;
            $hasil_training[$i]['prob_spam'] = $row->prob_spam_b;
            $i++;
        }
        
        
        
        // praproses tweet uji
        // $tweet = $this->removeUsernameHashtag($tweet);
        // $tweet = $this->hapusURL($tweet);
        // $tweet = $this->fungsiHapussimbol($tweet);
        // $tweet = $stemmer->stem($tweet);
        //memecah data uji tweet menjadi token

        $term_data_test = explode(" ", $tweet);
        
        //merapikan token
        $term_data_test = $this->merapikan_token($term_data_test);
        
        //menghitung jumlah token dari hasil training
        $jumlah_token_training = count($hasil_training);
        
        #untuk mengkoreksi pengali

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
        
        // return dd($pengali);
        // menentukan kelas
        if($probabilitas_spam > $probabilitas_valid){
            $kelas = "spam" ;
        }elseif ($probabilitas_valid > $probabilitas_spam) {
            $kelas = "valid" ;
        }else{
            $kelas = "bebas";
        }

        return array($kelas,$probabilitas_valid,$probabilitas_spam);

        //mengembalikan ke view
        // return View::make('klasifikasi.bernoullisatu')
        //     ->with('data_uji',$tweet_unclassified)
        //     ->with('kelas',$kelas)
        //     ->with('probabilitas_valid',$probabilitas_valid)
        //     ->with('probabilitas_spam',$probabilitas_spam)
        //     ->with('hasil_training',$hasil_training);
    }
    


    public function MultinomialSatu(){
        $stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();//load library stemmer sastrawi
        $stemmer  = $stemmerFactory->createStemmer();//load library stemmer sastrawi

        // $hasil_training = $this->fungsiMultinomial();//memanggil term hasil training
        $hasil_training_praproses = FiturPraproses::get();
        $i=0;
        foreach ($hasil_training_praproses as $row) {
        	$hasil_training[$i]['term'] = $row->term;
        	$hasil_training[$i]['prob_valid'] = $row->prob_valid_m;
        	$hasil_training[$i]['prob_spam'] = $row->prob_spam_m;
        	$i++;
        }

       
       
        // 2. Nih nih badannya seksi banget, jangan lupa nonton tuh sebelum hilang
        // 3. Doi badannya bohay dan seksi banget nih, anak kecil ndak boleh nonton tuh 
        // 3. Siapa yang tahan lihat cewek dengan badan seksi macam begini nih, dlvr.it/D3psKC  <-- KLIK DISINI
        // 4. Ini bocah beruntung banget nih punya mbak yang badannya seksi banget macam begini nih, tonton disini dlvr.it/D3psKC #bokep #3gp #seksi
        // 5. Badan siswi SMA ini seksi banget macam begini , semoga kamu kuat nontonnya http://goo.gl/st2JSx #dewasa #3gp #sange
        $tweet_unclassified = ""; //data tes
        $tweet = $this->removeUsernameHashtag($tweet_unclassified);
        $tweet = $this->hapusURL($tweet);
        $tweet = $this->fungsiHapussimbol($tweet);
        $tweet = $stemmer->stem($tweet);
        // return dd($tweet);
        

        //memecah data uji dan praproses
        $term_data_test = explode(" ", $tweet);
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
               
               	//pengali akan dimasukkan ke array $pengali, untuk kroscek saja
                array_push($pengali,$hasil_training[$index_vocab-1]['prob_spam']);
            }else{
            	//probabilitas term yang tidak digunakan akan dimasukkan ke array $pengali, untuk kroscek saja
                array_push($pengali,"kosong");
            }
           
        }
       	


        //menentukan kelas data uji
        if($probabilitas_spam > $probabilitas_valid){
            $kelas = "spam" ;
        }elseif ($probabilitas_valid > $probabilitas_spam) {
            $kelas = "valid" ;
        }else{
            $kelas = "bebas";
        }

        // return dd($probabilitas_valid);
    

        //mengembalikan ke view
        return View::make('klasifikasi.multinomialsatu')
            ->with('data_uji',$tweet_unclassified)
            ->with('kelas',$kelas)
            ->with('probabilitas_valid',$probabilitas_valid)
            ->with('probabilitas_spam',$probabilitas_spam)
            ->with('hasil_training',$hasil_training)
            ->with('tweet',$tweet);
                   
        
    }




    #proses mengklasifikasikan satu data uji dengan model bernoulli
    public function BernoulliSatu(){
        $stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();//load library stemmer sastrawi
        $stemmer  = $stemmerFactory->createStemmer();//load library stemmer sastrawi

        $jumlah = Datauji::where('kelas_asli','=',2)->count('id');
        // return dd($jumlah);
        //memanggil term hasil training bernoulli
        $hasil_training_praproses = FiturPraproses::get();
        $i=0;
        foreach ($hasil_training_praproses as $row) {
        	$hasil_training[$i]['term'] = $row->term;
        	$hasil_training[$i]['prob_valid'] = $row->prob_valid_b;
        	$hasil_training[$i]['prob_spam'] = $row->prob_spam_b;
        	$i++;
        }
        
        //tweet data tes
        $tweet_unclassified = "#lapor Hai min, ini penampakan tol cileunyi , ayo ditindaklanjuti dong, kan ngeri"; 
        
        $tweet = $this->removeUsernameHashtag($tweet_unclassified);
        $tweet = $this->hapusURL($tweet);
        $tweet = $this->fungsiHapussimbol($tweet);
        $tweet = $stemmer->stem($tweet);
        //memecah data uji tweet menjadi token

        $term_data_test = explode(" ", $tweet);
        
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
        
        // return dd($pengali);
        // menentukan kelas
        if($probabilitas_spam > $probabilitas_valid){
            $kelas = "spam" ;
        }elseif ($probabilitas_valid > $probabilitas_spam) {
            $kelas = "valid" ;
        }else{
            $kelas = "bebas";
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
    public function UjiMultinomialPraproses2(){
      
        $stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();//load library stemmer sastrawi
        $stemmer  = $stemmerFactory->createStemmer();//load library stemmer sastrawi

        $jumlah_spam = Datauji::where('kelas_asli','=',2)->count('id');
       
        //memanggil term hasil training multinomial
        $hasil_training_praproses = FiturPraproses2::get();
        $i=0;
        foreach ($hasil_training_praproses as $row) {
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
        
        //menentukan prior 
        $jumlah_spam = Datatraining2::where('kelas','=',2) //menghitung tweet spam
                ->count('id');
        $jumlah_valid = Datatraining2::where('kelas','=',1) //menghitung tweet valid
                ->count('id'); 
        $jumlah_tweet = Datatraining2::count('id');//menghitung jumlah tweet   

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



        return View::make('klasifikasi.ujimultinomial')->with('hasil_klasifikasi',$hasil_klasifikasi)
            ->with('true_positives',$true_positives)
            ->with('true_negatives',$true_negatives)
            ->with('false_negatives',$false_negatives)
            ->with('false_positives',$false_positives)
            ->with('precision',$precision)
            ->with('recall',$recall)
            ->with('f_measure',$f_measure)
            ->with('akurasi',$akurasi);
    }

  	#proses uji klasifikasi dengan multinomial
    public function UjiMultinomialPraproses(){
      
        $stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();//load library stemmer sastrawi
        $stemmer  = $stemmerFactory->createStemmer();//load library stemmer sastrawi

        
       
        //memanggil term hasil training multinomial
        $hasil_training_praproses = FiturPraproses::get();
        $i=0;
        foreach ($hasil_training_praproses as $row) {
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



        return View::make('klasifikasi.ujimultinomial')->with('hasil_klasifikasi',$hasil_klasifikasi)
            ->with('true_positives',$true_positives)
            ->with('true_negatives',$true_negatives)
            ->with('false_negatives',$false_negatives)
            ->with('false_positives',$false_positives)
            ->with('precision',$precision)
            ->with('recall',$recall)
            ->with('f_measure',$f_measure)
            ->with('akurasi',$akurasi)
            ->with('data'," ");
            ;
    }

    #proses uji klasifikasi dengan multinomial
    public function UjiMultinomialSeleksi(){
      
        $stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();//load library stemmer sastrawi
        $stemmer  = $stemmerFactory->createStemmer();//load library stemmer sastrawi

        
       
        //memanggil term hasil training multinomial
        $hasil_training_praproses = FiturSeleksi::get();
        $i=0;
        foreach ($hasil_training_praproses as $row) {
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
        // ujim dd($tweet_unclassified);
        
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


       

        return View::make('klasifikasi.ujimultinomial')->with('hasil_klasifikasi',$hasil_klasifikasi)
            ->with('true_positives',$true_positives)
            ->with('true_negatives',$true_negatives)
            ->with('false_negatives',$false_negatives)
            ->with('false_positives',$false_positives)
            ->with('precision',$precision)
            ->with('recall',$recall)
            ->with('f_measure',$f_measure)
            ->with('akurasi',$akurasi)
             ->with('data',"DATA TRAINING 1");
    }


    #proses uji klasifikasi dengan multinomial fitur seleksi data training 2
    public function UjiMultinomialSeleksi2(){
      
        $stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();//load library stemmer sastrawi
        $stemmer  = $stemmerFactory->createStemmer();//load library stemmer sastrawi

        
       
        //memanggil term hasil training multinomial
        $hasil_training_praproses = FiturSeleksi2::get();
        $i=0;
        foreach ($hasil_training_praproses as $row) {
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
        // ujim dd($tweet_unclassified);
        
        //membuat array untuk menyimpan token hasil training saja 
        $jumlah_token_training = count($hasil_training);
        for ($i=1; $i <= $jumlah_token_training ; $i++) { 
            $vocab[$i] = $hasil_training[$i-1]['term'];
        }
        
        //menentukan prior 
        $jumlah_spam = Datatraining2::where('kelas','=',2) //menghitung tweet spam
                ->count('id');
        $jumlah_valid = Datatraining2::where('kelas','=',1) //menghitung tweet valid
                ->count('id'); 
        $jumlah_tweet = Datatraining2::count('id');//menghitung jumlah tweet   

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

        
       

        return View::make('klasifikasi.ujimultinomial')->with('hasil_klasifikasi',$hasil_klasifikasi)
            ->with('true_positives',$true_positives)
            ->with('true_negatives',$true_negatives)
            ->with('false_negatives',$false_negatives)
            ->with('false_positives',$false_positives)
            ->with('precision',$precision)
            ->with('recall',$recall)
            ->with('f_measure',$f_measure)
            ->with('akurasi',$akurasi)             
            ->with('data',"DATA TRAINING 2");
    }

    #melakukan klasifikasi dengan metode bernoulli untuk banyak data
    public function ujiBernoulli(){
        $stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();//load library stemmer sastrawi
        $stemmer  = $stemmerFactory->createStemmer();//load library stemmer sastrawi

        //memanggil term hasil training multinomial
        $hasil_training_praproses = FiturPraproses::get();
        $i=0;
        foreach ($hasil_training_praproses as $row) {
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


        
        
          
        return View::make('klasifikasi.ujibernoulli')->with('hasil_klasifikasi',$hasil_klasifikasi)
            ->with('true_positives',$true_positives)
            ->with('true_negatives',$true_negatives)
            ->with('false_negatives',$false_negatives)
            ->with('false_positives',$false_positives)
            ->with('precision',$precision)
            ->with('recall',$recall)
            ->with('f_measure',$f_measure)
            ->with('akurasi',$akurasi)
            ->with('data'," ");
    }

    #melakukan klasifikasi dengan metode bernoulli untuk banyak data
    public function ujiBernoulliSeleksi2(){
        $stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();//load library stemmer sastrawi
        $stemmer  = $stemmerFactory->createStemmer();//load library stemmer sastrawi

        //memanggil term hasil training multinomial
        $hasil_training_praproses = FiturSeleksi2::get();
        $i=0;
        foreach ($hasil_training_praproses as $row) {
            $hasil_training[$i]['term'] = $row->term;
            $hasil_training[$i]['prob_valid'] = $row->prob_valid_b;
            $hasil_training[$i]['prob_spam'] = $row->prob_spam_b;
            $i++;
        }




        //memanggil data uji
        $data_uji = Datauji::get();
        $i = 0;
        foreach ($data_uji as $row) {
            $tweet_unclassified[$i][0] = $row->tweet;
            $tweet_unclassified[$i][1] = $row->kelas_asli;
            $i++;
        }
        
        //menghitung jumlah data uji
        $jumlah_data_uji = count($tweet_unclassified); 

        
        //menghitung jumlah token dari hasil training
        $jumlah_token_training = count($hasil_training);
        
        

        //menentukan prior 
        $jumlah_spam = Datatraining2::where('kelas','=',2) //menghitung tweet spam
                ->count('id');
        $jumlah_valid = Datatraining2::where('kelas','=',1) //menghitung tweet valid
                ->count('id'); 
        $jumlah_tweet = Datatraining2::count('id');//menghitung jumlah tweet   


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
        $akurasi = ($true_positives+$true_negatives)/ $jumlah_data_uji;
        $precision = $true_positives / ( $true_positives + $false_positives);
        $recall = $true_positives / ( $true_positives + $false_negatives);
        $f_measure = 2*$precision*$recall/($precision+$recall);


        
        
          
        return View::make('klasifikasi.ujibernoulli')->with('hasil_klasifikasi',$hasil_klasifikasi)
            ->with('true_positives',$true_positives)
            ->with('true_negatives',$true_negatives)
            ->with('false_negatives',$false_negatives)
            ->with('false_positives',$false_positives)
            ->with('precision',$precision)
            ->with('recall',$recall)
            ->with('f_measure',$f_measure)
            ->with('akurasi',$akurasi)
            ->with('data',"DATA TRAINING 2");
    }

    #melakukan klasifikasi dengan metode bernoulli untuk banyak data
    public function ujiBernoulliSeleksi(){
        $stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();//load library stemmer sastrawi
        $stemmer  = $stemmerFactory->createStemmer();//load library stemmer sastrawi

        //memanggil term hasil training multinomial
        $hasil_training_praproses = FiturSeleksi::get();
        $i=0;
        foreach ($hasil_training_praproses as $row) {
            $hasil_training[$i]['term'] = $row->term;
            $hasil_training[$i]['prob_valid'] = $row->prob_valid_b;
            $hasil_training[$i]['prob_spam'] = $row->prob_spam_b;
            $i++;
        }




        //memanggil data uji
        $data_uji = Datauji::get();
        $i = 0;
        foreach ($data_uji as $row) {
            $tweet_unclassified[$i][0] = $row->tweet;
            $tweet_unclassified[$i][1] = $row->kelas_asli;
            $i++;
        }
        
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


        
        
          
        return View::make('klasifikasi.ujibernoulli')->with('hasil_klasifikasi',$hasil_klasifikasi)
            ->with('true_positives',$true_positives)
            ->with('true_negatives',$true_negatives)
            ->with('false_negatives',$false_negatives)
            ->with('false_positives',$false_positives)
            ->with('precision',$precision)
            ->with('recall',$recall)
            ->with('f_measure',$f_measure)
            ->with('akurasi',$akurasi)
            ->with('data',"DATA TRAINING 1");
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

    //normalisasi kata dan karakter yang berulang
    public function normalisasiKata($input){
       
        #menghapus karakter yang berulang 
        $input = preg_replace('/(.)\1{2,}/', "$1", $input);

        #menormalkan kata menjadi bentuk baku
        
        //memecah kalimat
        $kata = explode(" ",$input);
        $jumlah_kata = count($kata);

        for ($i=0; $i < $jumlah_kata; $i++) { 
            $cek_tidak_baku = TabelKata::where('sebelum','=',$kata[$i])->pluck('sebelum');
            if ($cek_tidak_baku != null) {
              $kata_normal[$i] = TabelKata::where('sebelum','=',$kata[$i])->pluck('sesudah');
            }else{
              $kata_normal[$i] = $kata[$i];
            }
        } 
        
        //mengembalikan ke bentuk kata
        $kata_normal = implode(" ",$kata_normal);    
        return $kata_normal;
        
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


	