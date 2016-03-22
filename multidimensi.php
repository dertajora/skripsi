<?php 
$kata = array("Derta","Zamah","Resa");
$rambut = array("gundul","rontok","lurus");
print_r($kata);
print_r($rambut);
$tangan = "gendut";
$mata = "hitam";
$tangan1 = "gendut";
$mata1 = "hitam";

$array1 = array($tangan1,$mata1);

$tweet = array(array("tangan","mata"));

array_push($tweet, $array1 );
echo "<pre>";
print_r($tweet);
echo "</pre>";
?>