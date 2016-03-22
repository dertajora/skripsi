@extends('layout.skripsi')
@section('head')
 
@endsection
@section('judul')
	<b>KLASIFIKASI BERNOULLI</b>
@endsection

@section('content')
<?php 

// create stemmer
// cukup dijalankan sekali saja, biasanya didaftarkan di service container
$stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();
$stemmer  = $stemmerFactory->createStemmer();


$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';





$mysqli = new mysqli($dbhost, $dbuser, $dbpass, "skripsi");//syntax SQL untuk koneksi mysqli

/* check connection */
if ($mysqli->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}

$query = "SELECT id, tweet,kelas FROM data_training where kelas=1";
$result = $mysqli->query($query);//untuk menjalankan syntax mysqli

  
?>
<!-- <hr>
TOKEN VALID
<hr> -->
<?php 

$i=0;
$kelas_china = array();
while($row = $result->fetch_array(MYSQLI_ASSOC)) { //melakukan perulangan berdasarkan row yang di retrieve
	$kelas_china[$i]['tweet'] = $row['tweet'];
	$kelas_china[$i]['kelas'] = $row['kelas'];
	$i=$i+1;
}
$count = count($kelas_china); // menghitung jumlah tweet dalam kelas China


$token_china = array();
$jumlah_term_china = 0;
$jumlah_array = count($kelas_china);
for ($i=0; $i < $jumlah_array; $i++) {

	$terms_china = explode(" ", strtolower($kelas_china[$i]['tweet'])); //memecah kalimat menjadi kata 
	$jumlah	= count($terms_china); //menghitung jumlah term pada kelas China
    $jumlah_term_china = $jumlah_term_china + $jumlah;
	for ($j=0; $j < $jumlah; $j++) {
		
			array_push($token_china, $stemmer->stem($terms_china[$j])); //memasukkan kata ke array token
		
	}
}

// echo "Jumlah tweet dalam kelas China ".$count;
// echo "<br>";
// echo "Jumlah Term Kelas China = ".$jumlah_term_china;
// echo "<br>";
// print_r($token_china);	
?>
<!-- <hr>
TOKEN SPAM
<hr> -->

<?php 
$query = "SELECT id, tweet,kelas FROM data_training where kelas=2";
$result = $mysqli->query($query);

$i=0;
$kelas_japan = array();
while($row = $result->fetch_array(MYSQLI_ASSOC)) { 
	$kelas_japan[$i]['tweet'] = $row['tweet'];
	$kelas_japan[$i]['kelas'] = $row['kelas'];
	$i=$i+1;
}
$count_japan = count($kelas_japan); // menghitung jumlah tweet dalam kelas Jepang

$jumlah_term_japan = 0;
$token_japan = array();
$jumlah_array = count($kelas_japan);

for ($i=0; $i < $count_japan; $i++) { 
	$terms_japan = explode(" ", strtolower($kelas_japan[$i]['tweet'])); //mengubah karakter menjadi kecil dan di explode
	$jumlah = count($terms_japan); 
	$jumlah_term_japan = $jumlah_term_japan + $jumlah ; //menghitung jumlah term japan
	for ($j=0; $j < $jumlah ; $j++) { 
		array_push($token_japan, $stemmer->stem($terms_japan[$j]));//memasukkan term ke dalam daftar token dan stemming
	}
}

// echo "Jumlah tweet dalam kelas Japan ".$count_japan;
// echo "<br>";
// echo "Jumlah Term pada Kelas China = ".$jumlah_term_japan;
// echo "<br>";
// print_r($token_japan);	



#vocabulary
echo "<hr><b>
VOCABULARY</b>
<hr>";

$query = "SELECT id, tweet,kelas FROM data_training";
$result = $mysqli->query($query);

$i=0;
$kelas_vocab = array();
while($row = $result->fetch_array(MYSQLI_ASSOC)) { 
	$kelas_vocab[$i]['tweet'] = $row['tweet'];
	$kelas_vocab[$i]['kelas'] = $row['kelas'];
	$i=$i+1;
}
$count_vocab = count($kelas_vocab);

$jumlah_term_vocab = 0;
$token_vocab = array();


for ($i=0; $i < $count_vocab; $i++) { 
	$terms_vocab = explode(" ", strtolower($kelas_vocab[$i]['tweet']));
	$jumlah = count($terms_vocab);
	$jumlah_term_vocab = $jumlah_term_vocab + $jumlah ;
	for ($j=0; $j < $jumlah ; $j++) { 
		array_push($token_vocab, $terms_vocab[$j]);
	}
}

// echo "Jumlah tweet adalah ".$count_vocab;
// echo "<br> Token Awal";
// print_r($token_vocab);
$vocab_unique = array_unique($token_vocab); //mengeliminasi array yang nilainya sama
$vocab_reindexed = array_values($vocab_unique); // mengurutkan index 
// echo "<br> Token Unik";	
print_r($vocab_reindexed);	

$jumlah_vocab = count($vocab_reindexed);
// echo "<br>";	
// echo "Jumlah Vocabulary adalah = ". $jumlah_vocab;


echo "<hr><b>
PRIOR PROBABILITY</b>
<hr>";

$query2 = "SELECT count(id) as jumlah_dokumen FROM data_training";
$result2 = $mysqli->query($query2);
$dokumen=mysqli_fetch_assoc($result2);//mengambil jumlah dari seluruh dokumen

echo "Jumlah Dokumen Kelas China = ".$count."<br>";
echo "Jumlah Dokumen Kelas Japan = ".$count_japan."<br>";
echo "Jumlah Dokumen = ".$dokumen['jumlah_dokumen']."<br>";


$prior_china = $count / $dokumen['jumlah_dokumen'];
$prior_japan = $count_japan / $dokumen['jumlah_dokumen'];

echo "Nilai prior probability pada kelas China = ".$prior_china;
echo "<br>";
echo "Nilai prior probability pada kelas Jepang = ".$prior_japan;
echo "<br>";

#Training

echo "<hr><b>TRAINING VOCABULARY</b>
<hr>";

$hasil_training = array();
#training baru untuk vocabulary
for ($i=0; $i < $jumlah_vocab; $i++) { 
	#china
	// echo $vocab_reindexed[$i]."|";
	$kelas_mengandung_c = 0;
	for ($j=0; $j < $count ; $j++) { 
		
		if (strpos(strtolower($kelas_china[$j]['tweet']),$vocab_reindexed[$i]) !== false) { // mengecek tweet pada kelas c mengandung term x tidak, sebelumnya di strtolower
    		$kelas_mengandung_c = $kelas_mengandung_c+1; // jika mengandung maka jumlah kelas c yang mengandung x ditambah 1
		}
	
	}
	$prob_term_china = ($kelas_mengandung_c + 1)/($count + 2); // menghitung probabilitas term untuk kelas china
	

	#japan
	$kelas_mengandung_j = 0;
	for ($k=0; $k < $count_japan ; $k++) { 
		if (strpos(strtolower($kelas_japan[$k]['tweet']),$vocab_reindexed[$i]) !== false) { // mengecek tweet pada kelas j mengandung term x tidak, sebelumnya di strtolower
    		$kelas_mengandung_j = $kelas_mengandung_j+1; // jika mengandung maka jumlah kelas j yang mengandung x ditambah 1
		}
		
	}
	$prob_term_japan = ($kelas_mengandung_j + 1)/($count_japan + 2); // menghitung probabilitas term untuk kelas japan
	
    $hasil_training[$i]['term']=$vocab_reindexed[$i]; // dimasukkan ke array
    $hasil_training[$i]['prob_china']=$prob_term_china; // dimasukkan ke array
    $hasil_training[$i]['prob_japan']=$prob_term_japan; // dimasukkan ke array

}

print"<pre>";
print_r($hasil_training);
print"</pre>";
echo "<hr>";


#data uji
$data_uji = "Chinese Chinese Chinese Tokyo Japan";

echo "<b>DATA UJI : ".$data_uji."</b>";
echo "<hr>";

#klasifikasi
echo "<b>KLASIFIKASI DOKUMEN</b> ( Menghitung probabilitas masing-masing term ) ";
echo "<br>";

$terms_data_uji = explode(" ",strtolower($data_uji)); //memecah data uji menjadi array
// print_r($terms_data_uji);
$terms_data_uji = array_unique($terms_data_uji); //menghilangkan term yang berulang
// print_r($terms_data_uji);
$terms_data_uji = array_values($terms_data_uji); //mengurutkan index lagi dari array term
echo "Term data uji : ";print_r($terms_data_uji);
$jumlah_term_uji = count($terms_data_uji); // menghitung ada berapa kata pada data uji

$jumlah_terms_vocab = count($hasil_training); // menghitung jumlah term pada vocabulary

$probabilitas_china = 1 * $prior_china; //menghitung probabilitas china
$probabilitas_japan = 1 * $prior_japan; //menghitung probabilitas japan
echo "<br>";
echo $probabilitas_china;

for ($i=0; $i < $jumlah_terms_vocab; $i++) { //melakukan perulangan untuk setiap term pada vocab

	#pengali untuk probabilitas china dan japan
	if (in_array($hasil_training[$i]['term'],$terms_data_uji )) { // apakah term vocab i ada pada array term data uji ?
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
echo " = ".$probabilitas_china;
echo "<br>";
echo "Probabilitas Dokumen terhadap Kelas China = ".$probabilitas_china; //menampilkan probabilitas terpilih
echo "<br>";
echo "Probabilitas Dokumen terhadap Kelas Japan = ".$probabilitas_japan; //menampilkan probabilitas terpilih
echo "<br>";

echo "<hr>";
echo "<b>KESIMPULAN : </b>";
if ($probabilitas_china > $probabilitas_japan) { //membandingkan probabilitas apakah termasuk ke kelas china atau japan
	echo "Maka Dokumen tersebut termasuk ke kelas <b>China";
}else{
	echo "Maka Dokumen tersebut termasuk ke kelas <b>Jepang";
}
echo "</b>";
#
?>


@endsection





