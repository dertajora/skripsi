<?php


#query untuk menghitung jumlah tweet, tweet valid dan tweet spam

$query = "SELECT count(id) as jumlah_dokumen FROM data_training";
$result = $mysqli->query($query);//untuk menjalankan syntax mysqli
$count=mysqli_fetch_assoc($result);//mengambil jumlah dari seluruh dokumen
$jumlah_dokumen = $count['jumlah_dokumen'];

$query1 = "SELECT count(id) as valid FROM data_training where kelas=1";
$result1 = $mysqli->query($query1);//untuk menjalankan syntax mysqli
$count1=mysqli_fetch_assoc($result1);//mengambil tweet kelas valid
$jumlah_valid = $count1['valid'];

$query2 = "SELECT count(id) as spam FROM data_training where kelas=2";
$result2 = $mysqli->query($query2);//untuk menjalankan syntax mysqli
$count2=mysqli_fetch_assoc($result2);//mengambil tweet kelas spam
$jumlah_spam = $count1['spam'];

##mencari daftar term-term pada kelas valid
$i=0;
$kelas_valid = array();
while($row = $result1->fetch_array(MYSQLI_ASSOC)) { 
	$kelas_valid[$i]['tweet'] = $row['tweet'];
	$kelas_valid[$i]['kelas'] = $row['kelas'];
	$i=$i+1;
}
$jumlah_valid = count($kelas_valid); // menghitung jumlah tweet dalam kelas valid
$jumlah_term_valid = 0;

$token_valid = array();


for ($i=0; $i < $jumlah_valid; $i++) { 
	//mengubah karakter menjadi kecil dan di explode
	$terms_valid = explode(" ", strtolower($kelas_valid[$i]['tweet'])); 
	$jumlah = count($terms_spam); 
	$jumlah_term_valid = $jumlah_term_valid + $jumlah ; //jumlah term spam
	for ($j=0; $j < $jumlah ; $j++) { 
		//memasukkan term ke dalam daftar token dan stemming
		array_push($token_valid, $stemmer->stem($terms_valid[$j]));
	}
}


##mencari term-term pada kelas spam
$i=0;
$kelas_spam = array();
while($row = $result2->fetch_array(MYSQLI_ASSOC)) { 
	$kelas_spam[$i]['tweet'] = $row['tweet'];
	$kelas_spam[$i]['kelas'] = $row['kelas'];
	$i=$i+1;
}
$jumlah_spam = count($kelas_spam); // menghitung jumlah tweet dalam kelas spam

$jumlah_term_spam = 0;

$token_spam = array();


for ($i=0; $i < $jumlah_spam; $i++) { 
	 //mengubah karakter menjadi kecil dan di explode
	$terms_spam = explode(" ", strtolower($kelas_spam[$i]['tweet']));
	$jumlah = count($terms_spam); 
	$jumlah_term_spam = $jumlah_term_spam + $jumlah ; //jumlah term spam
	for ($j=0; $j < $jumlah ; $j++) { 
		//memasukkan term ke dalam daftar token dan stemming
		array_push($token_spam, $stemmer->stem($terms_spam[$j]));
	}
}


#menghitung nilai prior probability
$prior_valid = $jumlah_valid / $jumlah_dokumen;
$prior_spam = $jumlah_spam / $jumlah_dokumen;

#query untuk menampilkan data dari tb_feature
$query_data = "SELECT id, tweet FROM tb_feature";
$result_data = $mysqli->query($query_data);


$hasil_training = array();
while($row = $result_data->fetch_array(MYSQLI_ASSOC)) {


$hasil_training = array();
#training baru untuk vocabulary
for ($i=0; $i < $jumlah_vocab; $i++) { 

	#valid
	$kemunculan_term_c = 0;
	//untuk setiap tweet pada kelas valid
	for ($j=0; $j < $jumlah_term_valid ; $j++) { 
		//jika term pada kelas valid sama dengan term x
		if ($term_hasil_seleksi[$i]['term'] == $terms_valid[$j]) {
		   //kemunculan term pada kelas valid  ditambah 1	
		   $kemunculan_term_c = $kemunculan_term_c+1;	
		}
		
	}
	//menghitung probabilitas untuk kelas valid
	$prob_term_valid = ($kemunculan_term_c + 1)/($jumlah_term_valid + $jumlah_vocab);

	
	#spam
	$kemunculan_term_j = 0;
	//untuk setiap tweet pada kelas spam
	for ($k=0; $k < $jumlah_term_spam ; $k++) { 
		//jika term pada kelas spam sama dengan term x
		if ($term_hasil_seleksi[$i]['term'] == $token_spam[$k]) {
		//kemunculan term pada kelas spam ditambah 1	
		   $kemunculan_term_j = $kemunculan_term_j+1;	
		}
		
	}		
	//menghitung probabilitas untuk kelas spam
	$prob_term_spam = ($kemunculan_term_j + 1)/($jumlah_term_spam + $jumlah_vocab);


	#valid
	$kelas_mengandung_c = 0;
	//untuk setiap tweet pada kelas valid
	for ($j=0; $j < $jumlah_valid ; $j++) { 
		// mengecek tweet pada kelas valid mengandung term x tidak, sebelumnya di strtolower
		if (strpos(strtolower($kelas_valid[$j]['tweet']),$term_hasil_seleksi[$i]['term']) !== false) { 
    		// jika ditemukan jumlah kelas valid yang mengandung term c  ditambah 1
    		$kelas_mengandung_c = $kelas_mengandung_c+1; 
		}
	
	}
	// menghitung probabilitas term untuk kelas valid model bernoulli
	$prob_term_valid_b = ($kelas_mengandung_c + 1)/($jumlah_tweet_valid + 2);
	




	#spam
	$kelas_mengandung_j = 0;
	//untuk setiap tweet pada kelas spam
	for ($k=0; $k < $jumlah_spam ; $k++) { 
		// mengecek tweet pada kelas spam mengandung term x tidak, sebelumnya di strtolower
		if (strpos(strtolower($kelas_spam[$k]['tweet']),$term_hasil_seleksi[$i]['term']) !== false) { 
    		// jika ditemukan jumlah kelas valid yang mengandung term c  ditambah 1
    		$kelas_mengandung_j = $kelas_mengandung_j+1; 
		}
		
	}
	// menghitung probabilitas term untuk kelas spam mode bernoulli
	$prob_term_spam_b = ($kelas_mengandung_j + 1)/($jumlah_tweet_spam + 2); 
	

	//query untuk update tb_feature	
	$query = "UPDATE tb_feature SET prob_valid_m = $prob_term_valid,
			  prob_spam_m =  $prob_term_spam,
			  prob_valid_b = $prob_term_valid_b,
			  prob_spam_b = $prob_term_spam_b WHERE id= $term_hasil_seleksi[$i]['term']";
			  	 
	$result = $mysqli->query($query);//untuk menjalankan syntax mysqli


	if($result){ 
	}else{
    	die('Error : ('. $mysqli->errno .') '. $mysqli->error);
	}




    $hasil_training[$i]['term']=$vocab_reindexed[$i]; // dimasukkan ke array
    $hasil_training[$i]['prob_china']=$prob_term_china; // dimasukkan ke array
    $hasil_training[$i]['prob_japan']=$prob_term_japan; // dimasukkan ke array

	#prior
    $prior_valid = $jumlah_valid / $jumlah_dokumen;
	$prior_spam = $jumlah_spam / $jumlah_dokumen;

	#klasifikasi multinomial

	#memproses tweet data uji
	$terms_data_uji = explode(" ",strtolower($data_uji[$i]['tweet']));
	$jumlah_term_data_uji = count($terms_data_uji);

	//inisiasi awal nilai probabilitas 
	$probabilitas_china = 1 * $prior_china;
	$probabilitas_japan = 1 * $prior_japan;

	//menghitung setiap term pada tweet data uji 
	for ($i=0; $i < $jumlah_term_data_uji; $i++) { 
		// mencari apakah term data uji ada pada feature hasil seleksi atau tidak
		$index = array_search($terms_data_uji[$i], $hasil_training);


		if ($key == null) {
			//ketika term tidak ditemukan pada feature hasil seleksi
			//proses penghitungan didahului dengan pencarian probabilitas term 
			$probabilitas_valid = $probabilitas_valid * hitung_prob_valid($terms_data_uji[$i]);
			$probabilitas_spam = $probabilitas_spam * hitung_prob_spam($terms_data_uji[$i]);
		}else{
			//ketika term ditemukan pada feature hasil seleksi
			$probabilitas_valid = $probabilitas_valid * $hasil_training[$index]['prob_valid_m'];
			$probabilitas_spam = $probabilitas_spam * $hasil_training[$index]['prob_spam_m'];
		}
		
	}	


	//perbandingan
	if ($probabilitas_spam > $probabilitas_valid) {
		$kelas_sistem = 2;
	}else{
		$kelas_sistem = 1;



	#klasifikasi bernoulli

	#memproses tweet data uji
	$terms_data_uji = explode(" ",strtolower($data_uji[$i]['tweet']));
	$jumlah_term_data_uji = count($terms_data_uji);

	//inisiasi awal nilai probabilitas 
	$probabilitas_china = 1 * $prior_china;
	$probabilitas_japan = 1 * $prior_japan;

	//menghitung setiap term pada tweet data uji 
	for ($i=0; $i < $jumlah_term_data_uji; $i++) { 
		// mencari apakah term data uji ada pada feature hasil seleksi atau tidak
		$index = array_search($terms_data_uji[$i], $hasil_training);




		

		
		if (in_array($hasil_training[$i]['term'],$terms_data_uji )) { 
			$pengali = $hasil_training[$i]['prob_china']; //jika ada pada array maka pengali = probabilitas term i untuk kelas china
			$pengali_japan = $hasil_training[$i]['prob_japan']; //jika ada pada array maka pengali = probabilitas term i untuk kelas japan
		}else{
			$pengali = 1 - $hasil_training[$i]['prob_china'];//jika tidak ada pada array maka pengali = 1 - probabilitas term i untuk kelas china
			$pengali_japan = 1 - $hasil_training[$i]['prob_japan']; //jika tidak ada pada array maka pengali = 1 - probabilitas term i untuk kelas japan
		}

	// echo $pengali."<br>"; membuktikan pengali yang dipilih, apakah 1-P atau P


	echo " * ".$pengali; // menampilkan pengali yang dipilih, apakah 1-P atau P
	$probabilitas_china = $probabilitas_china * $pengali;  // probabilitas baru china = probabilitas dikali pengali
	$probabilitas_japan = $probabilitas_japan * $pengali_japan; // probabilitas baru japan = probabilitas dikali pengali


		
	}	

}

}

}