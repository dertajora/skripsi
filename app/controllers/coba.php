<?php 

//load library stemmer sastrawi
$stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();
$stemmer  = $stemmerFactory->createStemmer();
//melakukan stemming pada term
$str = $stemmer->stem($str);

//load token hasil praproses
$fitur_praproses = FiturPraproses::get();
$i=0;
foreach ($fitur_praproses as $row) {
  $token[$i] = $row->term;
  $i++;
} 

//menghitung jumlah tweet 
$jumlah_tweet = Datatraining::count('id');
//meretrieve tweet spam
$jumlah_valid = Datatraining::where('kelas','=',1)->count('id'); 
//meretrieve tweet spam
$jumlah_spam = Datatraining::where('kelas','=',2)->count('id'); 

//hitung A
foreach ($data_valids as $row) {

	$tweet = strtolower($stemmer->stem($row->tweet));            
    $cek_kemunculan = "/\b$kata\b/";
                
    if(preg_match($cek_kemunculan, $tweet)) {
        $jumlah_A = $jumlah_A+1;
    }
}

//hitung B
foreach ($data_spams as $row) {
    $tweet = strtolower($stemmer->stem($row->tweet));
    $cek_kemunculan = "/\b$kata\b/";
    
    if(preg_match($cek_kemunculan, $tweet)) {
        $jumlah_B = $jumlah_B+1;
    }
}


//C dan D
#mencari jumlah C
$jumlah_C = $jumlah_valid-$jumlah_A;
#mencari jumlah D
$jumlah_D = $jumlah_spam-$jumlah_B;

//seleksi nilai kritis
if ($token_seleksi[$i]['nilai_chi']>2.73) {
    $token_fix[$i] = $kata ;
}

#training multinomial
//meretrieve tweet valid
$data_valids = Datatraining::where('kelas','=',1)->get(); 
//meretrieve tweet spam
$data_spams = Datatraining::where('kelas','=',2)->get(); 
//meretrieve tweet
$data_tweets = Datatraining::get(); 
         
//membuat token valid dan spam
$token_valid = $this->maketoken($data_valids);
$token_spam = $this->maketoken($data_spams);

//menghitung jumlah token valid dan token spam 
$jumlah_token_valid = count($token_valid);
$jumlah_token_spam = count($token_spam);


         $term_valid = array(0);
         foreach ($data_valids as $row) {
             $terms_valid = explode(" ", strtolower($row->tweet));
             $jumlah = count($terms_valid); 
            
             for ($j=0; $j < $jumlah ; $j++) { 
                $str = $this->removeUsernameHashtag($terms_valid[$j]);
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
             $terms_spam = explode(" ", strtolower($row->tweet));
             $jumlah = count($terms_spam);

             
             for ($j=0; $j < $jumlah ; $j++) { 
                $str = $this->removeUsernameHashtag($terms_spam[$j]);
                $str = $this->hapusURL($str);
                $str = $this->fungsiHapussimbol($str);
                $str = $this->removeCommonWords($str);
                $str = $stemmer->stem($str);
                $terms = explode(" ",$str);

                $jumlah_term = count($terms);
                if ($jumlah_term > 1) {
                for ($k=0; $k < $jumlah_term ; $k++) { 
                    //memasukkan term term ke dalam daftar term valid
                    array_push($term_spam,$terms[$k]);
                    }
                }else{
                    //memasukkan term ke dalam daftar term valid
                    array_push($term_spam,$terms[0]);
                }  
             }
         }