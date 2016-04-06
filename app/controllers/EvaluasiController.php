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


    public function CrossValidation(){

        $start = microtime(true);
    
        $this->MembagiData();
        $this->CrossTrainingData();
        $akurasi_multinomial = $this->CrossKlasifikasiMultinomial();
        // print_r($akurasi_multinomial);
        $average_of_multinomial = array_sum($akurasi_multinomial) / count($akurasi_multinomial);
        // echo "Akurasinya adalah".$average_of_multinomial;
        // echo "<br>";
        $akurasi_bernoulli = $this->CrossKlasifikasiBernoulli();

        // print_r($akurasi_bernoulli);
        $average_of_bernoulli = array_sum($akurasi_bernoulli) / count($akurasi_bernoulli);
        // echo "Akurasinya adalah".$average_of_bernoulli;
        $end = microtime(true);
        $time = number_format(($end - $start), 2);
        // return dd($time);

        return View::make('evaluasi.ujicrossvalidation')
            ->with('array',$akurasi_multinomial)
            ->with('array1',$akurasi_bernoulli)
            ->with('average_of_multinomial',$average_of_multinomial)
            ->with('average_of_bernoulli',$average_of_bernoulli)
            ->with('time',$time);

        
    }

    
    public function CrossDatatest(){


      
         $data_trainings = json_decode(file_get_contents('cross/data_test0.json'), true);
         return View::make('data.cross-data-training')->with('data_trainings',$data_trainings);
         // return View::make('skripsi.data-training'); 
    }

    public function CrossDatatraining(){

      
         $data_trainings = json_decode(file_get_contents('cross/data_training0.json'), true);
         return View::make('data.cross-data-training')->with('data_trainings',$data_trainings);
         // return View::make('skripsi.data-training'); 
    }

    public function CrossFitur(){
         $loop = 2;
        // for ($loop=0; $loop < 6 ; $loop++) { 
        $vocab = [];    
        $file_training = "cross/data_training".$loop.".json";
       
        // menghitung data training
        $data_trainings = json_decode(file_get_contents($file_training), true);
        $jumlah_tweet = count($data_trainings);
        $j = 0;
         for ($i=0; $i < $jumlah_tweet ; $i++) { 
             
             if ($data_trainings[$i]['kelas']==1) {
                 $tweet_valid[$j] = $data_trainings[$i]['tweet'];
                 $j=$j+1;
             }
             
         }

         $j = 0;
         for ($i=0; $i < $jumlah_tweet ; $i++) { 
             
             if ($data_trainings[$i]['kelas']==2) {
                 $tweet_spam[$j] = $data_trainings[$i]['tweet'];
                 $j=$j+1;
             }
             
         }

         
        $jumlah_spam = count($tweet_spam);
        $jumlah_valid = count($tweet_valid);
        
        
        
       

            //prior probability
            $probabilitas_valid = $jumlah_valid/$jumlah_tweet;
            $probabilitas_spam = $jumlah_spam/$jumlah_tweet;
         echo "valid<br>";
         print_r($probabilitas_valid);
         echo "spam<br>";
         print_r($probabilitas_spam);
         echo "<BR>";
         $hasil_trainings = json_decode(file_get_contents('cross/fitur-hasil-training2.json'), true);
         return View::make('evaluasi.cross-fitur')->with('token_seleksi',$hasil_trainings);


         return dd($hasil_trainings);
         return View::make('data.cross-data-training')->with('data_trainings',$data_trainings);

         // return View::make('skripsi.data-training'); 
    }

    public function MembagiData(){
        $stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();//load library stemmer sastrawi
        $stemmer  = $stemmerFactory->createStemmer();//load library stemmer sastrawi
        

         

        #MENGAMBIL DATA TRAINING, PRAPROSES DAN MENGACAK
        $data_training = Datatraining2::get();
       
        $data_ready = [];
        $i=0;
        foreach ($data_training as $row) {
            $tweet = strtolower($row->tweet);
            $tweet = $this->removeUsernameHashtag($tweet);
            $tweet = $this->hapusURL($tweet);
            $tweet = $this->fungsiHapussimbol($tweet);
            $tweet = $this->normalisasiKata($tweet);
            $tweet = $this->removeCommonWords($tweet);
            $tweet = $stemmer->stem($tweet);


            $data_ready[$i]['tweet'] = $tweet; 
            $data_ready[$i]['kelas'] = $row->kelas;
            $i++;
        }
        
        // mengacak array multidimensional
        $acak_array = shuffle($data_ready);

        
        #MEMBUAT SEGMEN SEGMEN 
        echo "DATA SIAP";
        echo "<br>";
        // print_r($data_ready);
        echo "<br>";
        $x = 0;
        $j = 1;
        for($j=0; $j <6;$j++ ){
            $data_olah = $data_ready; 
            $data_test = [];
            $data_training = [];
            for ($i=0; $i < 100; $i++) { 
                    
                    array_push($data_test,$data_olah[$x]); 
                    
                    unset($data_olah[$x]);
                    $x = $x+1;
                     

            }
            $data_training = array_values($data_olah);
            
            // ${"data_training".$j}  = $data_training;
            // ${"data_test".$j}  = $data_test;
            // $b = 0;
            $jumlah_tweet_valid = 0;
            $jumlah_tweet_spam = 0;

            //menghitung jumlah tweet spam dan valid
            $token_baru = $this->maketoken_fromarray_cross($data_training);
            $token_baru = $this->merapikan_token($token_baru);
            
            //meletakkan file ke dalam file json dan txt
            $nama_file = "cross/token".$j.".txt";
            $nama_file_training = "cross/data_training".$j.".json";
            $nama_file_test = "cross/data_test".$j.".json";
            file_put_contents($nama_file, serialize($token_baru));
            file_put_contents($nama_file_training,json_encode($data_training));
            file_put_contents($nama_file_test,json_encode($data_test));

           

           

            
        }

        // return "sukses";
        
        // return View::make("evaluasi.crossvalidation")
        //     ->with('data_ready',$data_ready)
        //     ->with('data_test0',$data_test0)
        //     ->with('data_training0',$data_training0)
        //     ->with('data_test1',$data_test1)
        //     ->with('data_training1',$data_training1)
        //     ->with('data_training2',$data_training2)
        //     ->with('data_training3',$data_training3);
        // return "sukses";
       

             
    } 


    public function CrossTrainingData(){
        
        for ($j=0; $j < 6 ; $j++) { 
            
        
        $nama_token = "cross/token".$j.".txt";
        $token = unserialize(file_get_contents($nama_token));

      
         //2.melakukan proses seleksi 
         //memanggil fungsi untuk seleksi fitur
         //dengan list bisa mengembalikan dua nilai sekaligus
         // list($token_matematis,$token_fix) = $this->fungsiSeleksi($token,$j);
         // $jumlah_token = count($token_fix);
         // list($hasil_training,$jumlah_term_spam,$jumlah_term_valid,$jumlah_token,$jumlah_token_valid,$jumlah_token_spam,$jumlah_valid,$jumlah_spam) = $this->fungsiTraining($token_fix,$j);
         
         //tanpa proses seleksi
         list($hasil_training,$jumlah_term_spam,$jumlah_term_valid,$jumlah_token,$jumlah_token_valid,$jumlah_token_spam,$jumlah_valid,$jumlah_spam) = $this->fungsiTraining($token,$j);
         

         //menyimpan hasil training ke file json
         $nama_file_training = "cross/fitur-hasil-training".$j.".json";
         file_put_contents($nama_file_training,json_encode($hasil_training));
         
         } 

         // return ;  

         // return View::make('evaluasi.cross-training')->with('hasil_training',$hasil_training)
         // ->with('hasil_training_seleksi',$hasil_training)
         // ->with('jumlah_term_valid',$jumlah_term_valid)
         // ->with('jumlah_term_spam',$jumlah_term_spam)
         // ->with('jumlah_token_valid',$jumlah_token_valid) 
         // ->with('jumlah_token_spam',$jumlah_token_spam) 
         // ->with('jumlah_valid',$jumlah_valid)
         // ->with('jumlah_spam',$jumlah_spam) 
         // ->with('jumlah_token',$jumlah_token); 

         // return View::make('seleksi.chi_square')->with('token',$token)
         //                                        ->with('token_fix',$token_fix)
         //                                        ->with('token_matematis',$token_matematis);
        
    }

    public function CrossKlasifikasiBernoulli(){
        $akurasi_total = [];
        $loop = 2;
        for ($loop=0; $loop < 6; $loop++) { 
           
            $file_training = "cross/data_training".$loop.".json";
            $file_test = "cross/data_test".$loop.".json";
            $file_fitur = "cross/fitur-hasil-training".$loop.".json";    
            //memanggil term hasil training multinomial
            $hasil_training = json_decode(file_get_contents($file_fitur), true);
           

            $tweet_unclassified = json_decode(file_get_contents($file_test), true);

            
            //membuat array untuk menyimpan token hasil training saja 
            $jumlah_token_training = count($hasil_training);
            for ($i=1; $i <= $jumlah_token_training ; $i++) { 
                $vocab[$i] = $hasil_training[$i-1]['term'];
            }

            // menghitung data training
            $data_trainings = json_decode(file_get_contents($file_training), true);
            $jumlah_tweet = count($data_trainings);
            $j = 0;
             for ($i=0; $i < $jumlah_tweet ; $i++) { 
                 
                 if ($data_trainings[$i]['kelas']==1) {
                     $tweet_valid[$j] = $data_trainings[$i]['tweet'];
                     $j=$j+1;
                 }
                 
             }

             $j = 0;
             for ($i=0; $i < $jumlah_tweet ; $i++) { 
                 
                 if ($data_trainings[$i]['kelas']==2) {
                     $tweet_spam[$j] = $data_trainings[$i]['tweet'];
                     $j=$j+1;
                 }
                 
             }

            //menghitung jumlah tweet spam, valid dan data uji 
            $jumlah_spam = count($tweet_spam);
            $jumlah_valid = count($tweet_valid);
            $jumlah_data_uji = count($tweet_unclassified);

        
        
        //menghitung jumlah token dari hasil training
        $jumlah_token_training = count($hasil_training);
        
        //perulangan klasifikasi bernoulli untuk masing-masing data uji
        for ($j=0; $j < $jumlah_data_uji ; $j++) { 
            //praproses tweet
            $tweet = $tweet_unclassified[$j]['tweet'];
                      
            
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
                    $pengali_valid = $hasil_training[$i]['prob_valid_b'];
                    $pengali_spam = $hasil_training[$i]['prob_spam_b'];
                //jika tidak ada, maka (1 - probabilitas token tersebut) digunakan sebagai pengali
                }elseif ($check_in_data == false) {
                    $pengali_valid = 1-$hasil_training[$i]['prob_valid_b'];
                    $pengali_spam = 1-$hasil_training[$i]['prob_spam_b'];
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

            if ($tweet_unclassified[$j]['kelas']==1) {
                $kelas_asli = "valid";
            }else{
                $kelas_asli = "spam";
            }
             //menyimpan hasil klasifikasi
            $hasil_klasifikasi[$j]['tweet'] = $tweet_unclassified[$j]['tweet'];
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
        // $precision = $true_positives / ( $true_positives + $false_positives);
        // $recall = $true_positives / ( $true_positives + $false_negatives);
        // $f_measure = 2*$precision*$recall/($precision+$recall);
        array_push($akurasi_total, $akurasi);

    }

    return $akurasi_total;

        
        
          
        // return View::make('klasifikasi.ujibernoulli')->with('hasil_klasifikasi',$hasil_klasifikasi)
        //     ->with('true_positives',$true_positives)
        //     ->with('true_negatives',$true_negatives)
        //     ->with('false_negatives',$false_negatives)
        //     ->with('false_positives',$false_positives)
        //     ->with('precision',$precision)
        //     ->with('recall',$recall)
        //     ->with('f_measure',$f_measure)
        //     ->with('akurasi',$akurasi)
        //     ->with('data',"DATA TRAINING 2");
    }


    public function CrossKlasifikasiMultinomial(){

        $akurasi_total = [];
        $loop = 0;
        for ($loop=0; $loop < 6 ; $loop++) { 
            $vocab = [];    
            $file_training = "cross/data_training".$loop.".json";
            $file_test = "cross/data_test".$loop.".json";
            $file_fitur = "cross/fitur-hasil-training".$loop.".json";    
            //memanggil term hasil training multinomial
            $hasil_training = json_decode(file_get_contents($file_fitur), true);
           

            $tweet_unclassified = json_decode(file_get_contents($file_test), true);

            
            //membuat array untuk menyimpan token hasil training saja 
            $jumlah_token_training = count($hasil_training);
            for ($i=1; $i <= $jumlah_token_training ; $i++) { 
                $vocab[$i] = $hasil_training[$i-1]['term'];
            }

            // menghitung data training
            $data_trainings = json_decode(file_get_contents($file_training), true);
            $jumlah_tweet = count($data_trainings);
            $j = 0;
             for ($i=0; $i < $jumlah_tweet ; $i++) { 
                 
                 if ($data_trainings[$i]['kelas']==1) {
                     $tweet_valid[$j] = $data_trainings[$i]['tweet'];
                     $j=$j+1;
                 }
                 
             }

             $j = 0;
             for ($i=0; $i < $jumlah_tweet ; $i++) { 
                 
                 if ($data_trainings[$i]['kelas']==2) {
                     $tweet_spam[$j] = $data_trainings[$i]['tweet'];
                     $j=$j+1;
                 }
                 
             }

             
            $jumlah_spam = count($tweet_spam);
            $jumlah_valid = count($tweet_valid);
            $jumlah_data_uji = count($tweet_unclassified);
            
            
            //melakukan perulangan klasifikasi untuk setiap data uji
            for ($j=0; $j < $jumlah_data_uji ; $j++) { 
                
            //memecah data uji dan praproses
                $tweet = $tweet_unclassified[$j]['tweet'];     
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
                        $probabilitas_valid = $probabilitas_valid * $hasil_training[$index_vocab-1]['prob_valid_m'];
                        $probabilitas_spam = $probabilitas_spam * $hasil_training[$index_vocab-1]['prob_spam_m'];
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

                if ($tweet_unclassified[$j]['kelas']==1) {
                    $kelas_asli = "valid";
                }else{
                    $kelas_asli = "spam";
                }

                //menyimpan hasil klasifikasi
                $hasil_klasifikasi[$j]['tweet'] = $tweet_unclassified[$j]['tweet'];
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
        
            // $precision = $true_positives / ( $true_positives + $false_positives);
            // $recall = $true_positives / ( $true_positives + $false_negatives);
            // $f_measure = 2*$precision*$recall/($precision+$recall);



            // return View::make('klasifikasi.ujimultinomial')->with('hasil_klasifikasi',$hasil_klasifikasi)
            //     ->with('true_positives',$true_positives)
            //     ->with('true_negatives',$true_negatives)
            //     ->with('false_negatives',$false_negatives)
            //     ->with('false_positives',$false_positives)
            //     ->with('precision',$precision)
            //     ->with('recall',$recall)
            //     ->with('f_measure',$f_measure)
            //     ->with('akurasi',$akurasi)
            //     ->with('data',"");
            array_push($akurasi_total, $akurasi);

        }

        return $akurasi_total;
    }


    public function fungsiTraining($token,$loop){
        //mengambil data training ke $Loop untuk setiap perulangan
         $nama_file = "cross/data_training".$loop.".json";
         $data_trainings = json_decode(file_get_contents($nama_file), true);
         // return dd($data_valids);
         
         $j = 0;

         $jumlah_data = count($data_trainings);
         for ($i=0; $i < $jumlah_data ; $i++) { 
             
             if ($data_trainings[$i]['kelas']==1) {
                 $tweet_valid[$j] = $data_trainings[$i]['tweet'];
                 $j=$j+1;
             }
             
         }

         $j = 0;
         for ($i=0; $i < $jumlah_data ; $i++) { 
             
             if ($data_trainings[$i]['kelas']==2) {
                 $tweet_spam[$j] = $data_trainings[$i]['tweet'];
                 $j=$j+1;
             }
             
         }

         
         $jumlah_spam = count($tweet_spam);
         $jumlah_valid = count($tweet_valid);
        
         
         //membuat  valid dan spam
         $token_valid = $this->maketoken_fromarray($tweet_valid);
         
         // app('App\Http\Controllers\SkripsiContoller')->maketoken();
         $token_spam = $this->maketoken_fromarray($tweet_spam);

         
         $jumlah_token_valid = count($token_valid);
         $jumlah_token_spam = count($token_spam);

        
         

         #proses menghitung jumlah term kelas valid
         
         $term_valid = [];
         for ($i=0; $i < $jumlah_valid; $i++) {
             $terms_valid = explode(" ", $tweet_valid[$i]); //mengexplode tweet
             $jumlah = count($terms_valid); 
            
             for ($j=0; $j < $jumlah ; $j++) { 
                //memasukkan term term ke dalam daftar term valid
                array_push($term_valid,$terms_valid[$j]);        
             }
         }
         
         //menghapus term spasi
         $term_valid = $this->merapikan_term($term_valid);

         #menghitung jumlah term kelas valid , spasi ndak dihitung
         $jumlah_term_valid = count($term_valid);
         
         

         #proses menghitung jumlah term kelas spam
         $term_spam = [];
         for ($i=0; $i < $jumlah_spam; $i++){
             $terms_spam = explode(" ", strtolower($tweet_spam[$i])); //mengexplode tweet
             $jumlah = count($terms_spam);

             
             for ($j=0; $j < $jumlah ; $j++) { 
                array_push($term_spam,$terms_spam[$j]);//memasukkan term term ke dalam daftar term spam    
             }
         }

         //menghapus term spasi
         $term_spam = $this->merapikan_term($term_spam);
         #menghitung jumlah term kelas spam , spasi ndak dihitung
         $jumlah_term_spam = count($term_spam);
         
         
         //menghitung jumlah token hasil seleksi
         $jumlah_token = count($token);
         $jumlah_token_valid = count($this->merapikan_token($token_valid));
         $jumlah_token_spam = count($this->merapikan_token($token_spam));
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
              
              $happened_valid = 0;
              $happened_spam = 0;

              $a=0;
              for ($a=0; $a < $jumlah_valid ; $a++) { 
                 $cek_kemunculan = "/\b$token[$i]\b/";
                  if(preg_match($cek_kemunculan, $tweet_valid[$a])) {
                    //jika iya jumlah kejadian happened_valid ditambah 1
                    $happened_valid = $happened_valid+1;
                  }
              }

              $b=0;
              for ($b=0; $b < $jumlah_spam ; $b++) { 
                 $cek_kemunculan = "/\b$token[$i]\b/";
                  if(preg_match($cek_kemunculan, $tweet_spam[$b])) {
                    //jika iya jumlah kejadian happened_valid ditambah 1
                    $happened_spam = $happened_spam+1;
                  }
              }

               //menghitung probabilitas  term terhadap kelas valid - bernoulli
              $prob_term_valid_b = ($happened_valid + 1)/($jumlah_valid + 2);

              //menghitung probabilitas term terhadap kelas spam - bernoulli
              $prob_term_spam_b = ($happened_spam + 1)/($jumlah_spam + 2);


              //menghitung probabilitas term terhadap kelas valid
              $prob_term_valid_m = ($kemunculan_di_valid + 1)/($jumlah_term_valid + $jumlah_token_valid);

              //menghitung probabilitas term terhadap kelas spam
              $prob_term_spam_m = ($kemunculan_di_spam + 1)/($jumlah_term_spam + $jumlah_token_spam);

              //menyimpan hasil training masing-masing term
              $hasil_training[$i]['term']=$token[$i];
              $hasil_training[$i]['kemunculan_valid']=$kemunculan_di_valid;
              $hasil_training[$i]['kemunculan_spam']=$kemunculan_di_spam;
              $hasil_training[$i]['happened_valid']=$happened_valid;
              $hasil_training[$i]['happened_spam']=$happened_spam;
              $hasil_training[$i]['prob_valid_m']=round($prob_term_valid_m,10);
              $hasil_training[$i]['prob_spam_m']=round($prob_term_spam_m,10);
              $hasil_training[$i]['prob_valid_b']=round($prob_term_valid_b,10);
              $hasil_training[$i]['prob_spam_b']=round($prob_term_spam_b,10);

             
         }
         
         return array($hasil_training,$jumlah_term_spam,$jumlah_term_valid,$jumlah_token,$jumlah_token_valid,$jumlah_token_spam,$jumlah_valid,$jumlah_spam);
    }
    public function fungsiSeleksi($token,$loop){
        $nama_file = "cross/data_training".$loop.".json";
         
         $jumlah_tweet = 500;//menghitung jumlah tweet    
         $data_trainings = json_decode(file_get_contents($nama_file), true);
         // return dd($data_valids);
         
         $j = 0;

         $jumlah_data = count($data_trainings);
         for ($i=0; $i < $jumlah_data ; $i++) { 
             
             if ($data_trainings[$i]['kelas']==1) {
                 $tweet_valid[$j] = $data_trainings[$i]['tweet'];
                 $j=$j+1;
             }
             
         }

         $j = 0;
         for ($i=0; $i < $jumlah_data ; $i++) { 
             
             if ($data_trainings[$i]['kelas']==2) {
                 $tweet_spam[$j] = $data_trainings[$i]['tweet'];
                 $j=$j+1;
             }
             
         }

         
         $jumlah_spam = count($tweet_spam);
         $jumlah_valid = count($tweet_valid);

         
         //menghitung jumlah token hasil praproses
         $jumlah_token = count($token);
         //mendefinisikan array baru
         
       
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

    

    public function maketoken_fromarray_cross($data_tweets){
          #seluruh tweet
         
         $i=0;
       
         $token= array();
         $jumlah_tweet = count($data_tweets);
        
         for ($i=0; $i < $jumlah_tweet; $i++) { 
            $terms = explode(" ", strtolower($data_tweets[$i]['tweet'])); //memecah tweet menjadi kata 
            $jumlah_term = count($terms);
            for ($j=0; $j < $jumlah_term ; $j++) { 
              //memasukkan term ke dalam daftar token
              //melakukan stemming untuk term  
              array_push($token, $terms[$j]);
            }  

         }
         return $token;

    }


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


    