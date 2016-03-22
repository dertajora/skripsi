<?php 
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$conn = mysql_connect($dbhost, $dbuser, $dbpass);
if(! $conn )
{
  die('Could not connect: ' . mysql_error());
}

mysql_select_db('skripsi');

#MENAMPILKAN DATA TRAINING

$sql_training = "select id,tweet,kelas from belajar_bayes";
$retrieve_training = mysql_query( $sql_training, $conn );
  
?>
<B>DATA TRAINING</B><BR><BR>
<table>
	<tr>
	<td><b>No</b></td>
	<td><b>Tweet</b></td>
	<td><b>Kelas</b></td>
	</tr>
<?php
while($row = mysql_fetch_array($retrieve_training, MYSQL_ASSOC)) { 
	echo "<tr>";
	echo "<Td>".$row['id']."</td>";
	echo "<Td>".$row['tweet']."</td>";
	echo "<Td>".$row['kelas']."</td>";
	echo "</tr>";
}
?>
</table>
<HR>
<?php
#prior probability
$sql_jumlah_china = mysql_query("select count(id) as jumlah from belajar_bayes where kelas=1"); //query mencari jumlah tweet dalam kelas china
$sql_jumlah_japan = mysql_query("select count(id) as jumlah from belajar_bayes where kelas=2"); //query mencari jumlah tweet dalam kelas japan
$sql_jumlah_tweet = mysql_query("select count(id) as jumlah from belajar_bayes"); //query mencari jumlah tweet

$result_china=mysql_fetch_assoc($sql_jumlah_china); 
$result_japan=mysql_fetch_assoc($sql_jumlah_japan);
$result_tweet=mysql_fetch_assoc($sql_jumlah_tweet);

$jumlah_tweet_china = $result_china['jumlah'];
$jumlah_tweet_japan = $result_japan['jumlah'];
$jumlah_tweet = $result_tweet['jumlah'];

echo "<b>Mencari Prior Probability</b><br><br>";
echo "Jumlah Tweet pada kelas China = ".$jumlah_tweet_china;
echo "<br>";
echo "Jumlah Tweet pada kelas Japan = ".$jumlah_tweet_japan;
echo "<br>";
echo "Jumlah Tweet  = ".$jumlah_tweet;
echo "<br>";

$prior_china = $jumlah_tweet_china / $jumlah_tweet;
$prior_japan = $jumlah_tweet_japan / $jumlah_tweet;

echo "Nilai prior probability pada kelas China = ".$prior_china;
echo "<br>";
echo "Nilai prior probability pada kelas Jepang = ".$prior_japan;
echo "<br>";

echo "<hr>";

#kelas china
echo "<b>Mencari Jumlah tweet dan term dalam kelas China</b><br><br>";
$sql_china = "select id,tweet,kelas from belajar_bayes where kelas=1";
      // $result = mysqli_query($sql,$link);
$retrieve_china = mysql_query( $sql_china, $conn );
    if(! $retrieve_china )
    {
      die('Could not enter data: ' . mysql_error());
    }

#kelas japan
$sql_japan = "select id,tweet,kelas from belajar_bayes where kelas=2";
$retrieve_japan = mysql_query( $sql_japan, $conn );
    if(! $retrieve_japan )
    {
      die('Could not enter data: ' . mysql_error());
    }

#kelas vocabulary

$sql_vocab = "select id,tweet,kelas from belajar_bayes";
$retrieve_vocab = mysql_query( $sql_vocab, $conn );
    if(! $retrieve_vocab )
    {
      die('Could not enter data: ' . mysql_error());
    }



#kelas china
$i=0;
$kelas_china = array();
while($row = mysql_fetch_array($retrieve_china, MYSQL_ASSOC)) { 
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
		
			array_push($token_china, $terms_china[$j]); //memasukkan kata ke array token
		
	}
}
echo "Jumlah tweet dalam kelas China ".$count;
echo "<br>";
echo "Jumlah Term Kelas China = ".$jumlah_term_china;
echo "<br>";
print_r($token_china);		
echo "<hr>";


#kelas jepang
$i=0;
$kelas_japan = array();
while($row = mysql_fetch_array($retrieve_japan, MYSQL_ASSOC)) { 
	$kelas_japan[$i]['tweet'] = $row['tweet'];
	$kelas_japan[$i]['kelas'] = $row['kelas'];
	$i=$i+1;
}
$count_japan = count($kelas_japan); // menghitung jumlah tweet dalam kelas Jepang

$jumlah_term_japan = 0;
$token_japan = array();
$jumlah_array = count($kelas_japan);

for ($i=0; $i < $count_japan; $i++) { 
	$terms_japan = explode(" ", strtolower($kelas_japan[$i]['tweet']));
	$jumlah = count($terms_japan);
	$jumlah_term_japan = $jumlah_term_japan + $jumlah ;
	for ($j=0; $j < $jumlah ; $j++) { 
		array_push($token_japan, $terms_japan[$j]);
	}
}

echo "<b>Mencari Jumlah tweet dan term dalam kelas Japan</b><br><br>";
echo "Jumlah tweet dalam kelas Japan ".$count_japan;
echo "<br>";
echo "Jumlah Term pada Kelas China = ".$jumlah_term_japan;
echo "<br>";
print_r($token_japan);		
echo "<hr>";

#vocabulary
$i=0;
$kelas_vocab = array();
while($row = mysql_fetch_array($retrieve_vocab, MYSQL_ASSOC)) { 
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
echo "<b>Mencari vocabulary</b><br><br>";
echo "Jumlah tweet adalah ".$count_vocab;
echo "<br>";
print_r($token_vocab);
$vocab_unique = array_unique($token_vocab);
$vocab_reindexed = array_values($vocab_unique);
echo "<br>";	
print_r($vocab_reindexed);	

$jumlah_vocab = count($vocab_reindexed);
echo "<br>";	
echo "Jumlah Vocabulary adalah = ". $jumlah_vocab;
echo "<hr>";


#training gagal
// $prob_term_china = array() ;
// $term_data_uji = explode(" ",strtolower($data_uji));
// $jumlah_data_uji = count($term_data_uji);
// for ($i=0; $i < $jumlah_data_uji; $i++) { 
// 	$jumlah_temporary = 0;
// 	for ($j=0; $j < $jumlah_term_china ; $j++) { 
		
// 		if ($term_data_uji[$i] == $token_china[$j]) {
// 		   $jumlah_temporary = $jumlah_temporary+1;	
// 		}
		
// 	}
// 	$prob_term = ($jumlah_temporary + 1)/($jumlah_term_china + $jumlah_vocab); 
// 	echo $prob_term;
// 	echo "<br>";
	
// }


echo "<b>Training Vocabulary</b> ( Mencari Probabilitas masing-masing term pada vocabulary ) ";
echo "<br>";

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

// print"<pre>";
// print_r($hasil_training);
// print"</pre>";
// echo "<hr>";

#data uji
$data_uji = "jalan jalan promo diskon seksi";
echo "<b>Data Uji : ".$data_uji."</b>";
echo "<hr>";

#klasifikasi
echo "<b>KLASIFIKASI DOKUMEN</b> ( Menghitung probabilitas masing-masing term ) ";
echo "<br>";

$terms_data_uji = explode(" ",strtolower($data_uji));
$jumlah_term_dokumen = count($terms_data_uji);

$probabilitas_china = 1 * $prior_china;
$probabilitas_japan = 1 * $prior_japan;
// echo $probabilitas_china;echo "<br>";
for ($i=0; $i < $jumlah_term_dokumen; $i++) { 
	$index_vocab = array_search($terms_data_uji[$i], $vocab_reindexed);// mencari apakah term dokumen uji ada pada vocabulary atau tidak
	// echo $index_vocab;
	$probabilitas_china = $probabilitas_china * $hasil_training[$index_vocab]['prob_china'];
	$probabilitas_japan = $probabilitas_japan * $hasil_training[$index_vocab]['prob_japan'];

	// echo $hasil_training[$index_vocab]['prob_china'];
	// echo "<br>";
}
echo "<br>";
echo "Probabilitas Dokumen terhadap Kelas China = ".$probabilitas_china;
echo "<br>";
echo "Probabilitas Dokumen terhadap Kelas Japan = ".$probabilitas_japan;
echo "<br>";

echo "<hr>";
echo "<b>KESIMPULAN : </b>";
if ($probabilitas_china > $probabilitas_japan) {
	echo "Maka Dokumen tersebut termasuk ke kelas <b>China";
}else{
	echo "Maka Dokumen tersebut termasuk ke kelas <b>Jepang";
}
echo "</b>";
#
?>