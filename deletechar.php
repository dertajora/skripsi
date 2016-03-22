<?php 
$string = "Hello world. Beautiful day today.";
 $hasil = preg_replace('/[^a-zA-Z|\- ]/','',$string);//menghilangkan karakter selain huruf "-" dan "|""
echo $string;
echo "<br>";
echo $hasil;
echo "<br>";

echo strtolower($hasil);
?>