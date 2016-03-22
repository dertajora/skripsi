<?php
// demo.php

// include composer autoloader
require_once __DIR__ . '/vendor/autoload.php';

// create stemmer
// cukup dijalankan sekali saja, biasanya didaftarkan di service container
$stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();
$stemmer  = $stemmerFactory->createStemmer();

// stem
$sentence = 'Perekonomian Indonesia sedang dalam pertumbuhan yang membanggakan. Pagi ini sangatlah cerah, daripada kemarin siang yang sangat menghangatkan';
$output   = $stemmer->stem($sentence);

echo $output . "\n";
// ekonomi indonesia sedang dalam tumbuh yang bangga
echo "<br>";
echo $stemmer->stem('Masyarakat Chinese Indonesia memiliki kebiasaan buruk membuang sampah sembarangan') . "\n";
// mereka tiru