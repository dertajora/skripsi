<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
ini_set("log_errors", 0);

$content = "This is a test string, which is used for

demonstrating the tokenization using PHP. PHP is a very (strong) scripting-language";

$words = array_unique(str_word_count(preg_replace('/-/', ' ', $content), 1));

print "<pre>";
print_r($words);
print "</pre>";
echo "<br>";

?>