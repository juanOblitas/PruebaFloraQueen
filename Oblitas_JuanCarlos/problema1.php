<?php
//@author Juan Carlos Oblitas Nuñez
$numbers=[];
define("MIN", 1);
define("MAX", 100);
for ($i=0; $i < constant("MAX"); $i++) { 
    $numbers[$i]=$i+constant("MIN");
}
echo "Numbers initial: ";
print_r($numbers);
echo "<br>";
$value=rand(1, constant("MAX"));
echo "get out a rand number: ".$value."<br>";
echo "In the bad stay the next numbers: ";
$numbers = array_diff($numbers, array($value));
print_r($numbers);
?>