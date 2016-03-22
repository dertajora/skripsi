@extends('layout.skripsi')
@section('head')
 
@endsection
@section('judul')
	<b>DATA UJI</b>
@endsection

@section('content')
	<?php 
	$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';





$mysqli = new mysqli($dbhost, $dbuser, $dbpass, "skripsi");

/* check connection */
if ($mysqli->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}

$query = "SELECT id, tweet,kelas FROM belajar_bayes";
$result = $mysqli->query($query);


#MENAMPILKAN DATA TRAINING

// $sql_training = "select id,tweet,kelas from data_training";
// $retrieve_training =  mysql_query( $sql_training, $conn );

// $query = "SELECT Name, CountryCode FROM City ORDER by ID LIMIT 3";
// $result = $mysqli->query($query);
  
?>
<B>Data Uji</B><BR><BR>
<table>
	<tr>
	<td><b>No</b></td>
	<td><b>Tweet</b></td>
	<td><b>Kelas</b></td>
	</tr>
<?php
while($row = $result->fetch_array(MYSQLI_ASSOC)) { 
	echo "<tr>";
	echo "<Td>".$row['id']."</td>";
	echo "<Td>".$row['tweet']."</td>";
	echo "<Td>".$row['kelas']."</td>";
	echo "</tr>";
}
?>
</table>


@endsection