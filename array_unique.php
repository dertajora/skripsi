<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
ini_set("log_errors", 0);

$content = "This is a test string, which is used for demonstrating the tokenization using PHP. PHP is a very (strong) scripting-language";

$words = array();
$delim = " \n";
$token = strtok($content, $delim);
while ($token !== false) {
  $words[] = $token;
  $token = strtok($delim);
}
$unique_words = array_unique($words);



print "<pre>";
print_r($unique_words);
print "</pre>";
echo "---<br>";
print "<pre>";
print_r(sort($unique_words));
print "</pre>";


?>