<?php

$fruits = array("lemon","orago", "orange", "banana", "apple");
sort($fruits);
print "<pre>";
foreach ($fruits as $key => $val) {
    echo "fruits[" . $key . "] = " . $val . "\n";
}
print "</pre>";

?>