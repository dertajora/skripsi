

<?php

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

//proses perulangan menghitung nilai chi square
$jumlah_token = count($token_reindexed);
$nilai_critical = 2.71; 
 
for ($i=0; $i < $jumlah_token; $i++) {  
	$term = $token_reindexed[$i];

	#menghitung nilai A

	#menghitung nilai B

	#menghitung nilai C

	#menghitung nilai D

	#menghitung nilai Chi Square

	#memasukkan ke array_term_chi
}

//
#mencari jumlah dokumen pada kelas valid yang mengandung term X (A)
...
$jumlah_A = 0;
	for ($k=0; $k < $jumlah_tweet_valid; $k++) { 
		
			if (strpos($kelas_valid[$k]['tweet'], $term) !== false) {
				$jumlah_A = $jumlah_A + 1;
			}
		
}
...

#mencari jumlah dokumen pada kelas spam yang mengandung term X (B)
...	
	$jumlah_B = 0;
	for ($k=0; $k < $jumlah_tweet_spam; $k++) { 
		
			if (strpos($kelas_spam[$k]['tweet'], $term) !== false) {
				$jumlah_B = $jumlah_B + 1;
			}
		
	}
...

...
#mencari jumlah dokumen pada kelas valid yang tidak mengandung term X
$jumlah_C = $jumlah_valid-$jumlah_A;
#mencari jumlah dokumen pada kelas spam yang tidak mengandung term X
$jumlah_D = $jumlah_spam-$jumlah_B;

#mencari nilai chi square
$penyebut = ($jumlah_A + $jumlah_B) * ( $jumlah_C+$jumlah_D )* ($jumlah_A+$jumlah_C) * ($jumlah_B+$jumlah_D); 
$pembilang_kanan = (($jumlah_A*$jumlah_D)-($jumlah_B*$jumlah_C)); //mencari pembilang bagian kanan 	

if ($penyebut == 0) {
		$nilai_chi = 0;
}else{
	$nilai_chi = $jumlah_dokumen * pow($pembilang_kanan,2) / $penyebut;  //menghitung nilai chi square
}
...

if ($nilai_chi >= $nilai_critical) {
	$query = "INSERT  into tb_feature (term) VALUES ('$term')";
	$result = $mysqli->query($query);//untuk menjalankan syntax mysqli
}

