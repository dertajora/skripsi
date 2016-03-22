@extends('layout.skripsi')
@section('head')
 
@endsection
@section('judul')
	<b>HASIL TRAINING</b>
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
<hr><b>
TOKEN VALID
</b>
<hr>
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

echo "Jumlah tweet dalam kelas China ".$count;
echo "<br>";
echo "Jumlah Term Kelas China = ".$jumlah_term_china;
echo "<br>";
print_r($token_china);	
?>
<hr><b>
TOKEN SPAM
</b><hr>

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

echo "Jumlah tweet dalam kelas Japan ".$count_japan;
echo "<br>";
echo "Jumlah Term pada Kelas China = ".$jumlah_term_japan;
echo "<br>";
print_r($token_japan);	



#vocabulary
echo "<hr>
<b>VOCABULARY</b>
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

echo "Jumlah tweet adalah ".$count_vocab;
echo "<br> Token Awal";
print_r($token_vocab);
$vocab_unique = array_unique($token_vocab);
$vocab_reindexed = array_values($vocab_unique);
echo "<br> Token Unik";	
print_r($vocab_reindexed);	

$jumlah_vocab = count($vocab_reindexed);
echo "<br>";	
echo "Jumlah Vocabulary adalah = ". $jumlah_vocab;


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
	$kemunculan_term_c = 0;
	for ($j=0; $j < $jumlah_term_china ; $j++) { 
		
		if ($vocab_reindexed[$i] == $token_china[$j]) {
		   $kemunculan_term_c = $kemunculan_term_c+1;	
		}
		
	}
	$prob_term_china = ($kemunculan_term_c + 1)/($jumlah_term_china + $jumlah_vocab);
	// echo $prob_term_china;
	// echo "|";

	#japan
	$kemunculan_term_j = 0;
	for ($k=0; $k < $jumlah_term_japan ; $k++) { 
		
		if ($vocab_reindexed[$i] == $token_japan[$k]) {
		   $kemunculan_term_j = $kemunculan_term_j+1;	
		}
		
	}
	$prob_term_japan = ($kemunculan_term_j + 1)/($jumlah_term_japan + $jumlah_vocab);
	// echo $prob_term_japan;
	// echo "<br>";
    $hasil_training[$i]['term']=$vocab_reindexed[$i];
    $hasil_training[$i]['prob_china']=$prob_term_china;
    $hasil_training[$i]['prob_japan']=$prob_term_japan;

}

print"<pre>";
print_r($hasil_training);
print"</pre>";
echo "<hr>";
?>


@endsection





