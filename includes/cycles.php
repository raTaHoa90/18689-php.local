<?php
$cars = ['bwm', 'audi', 'kia', 'vaz'];

$i = 0;
echo '<h3>WHILE</h3><ul>';
while($i < count($cars)) {
    echo '<li>'.$cars[$i].'</li>';
    //$i = $i + 1;
    //$i += 1;
    $i++;
}
echo '</ul><hr>';

$i = 0;
echo '<h3>DO WHILE</h3><ul>';
do {
    echo '<li>'.$cars[$i].'</li>';
    $i++;
} while ($i < count($cars));
echo '</ul><hr>';

echo '<h3>FOR</h3><ul>';
for($i = 0; $i < count($cars); $i++)
    echo '<li>'.$cars[$i].'</li>';
echo '</ul><hr>';

echo '<h3>FOREACH (value)</h3><ul>';
foreach($cars as $car) // JS => for(let car of cars)
    echo "<li>$car</li>";
echo '</ul><hr>';

echo '<h3>FOREACH (key => value) </h3><ul>';
foreach($cars as $i => $car)
    echo "<li>$car($i)</li>";
echo '</ul><hr>';

$arr = $cars; // $arr = clone $cars
$arr[] = 'kamaz'; 
    // $cars = ['bwm', 'audi', 'kia', 'vaz']; 
    // $arr = ['bwm', 'audi', 'kia', 'vaz', 'kamaz']
$arr = &$cars;
$arr[] = 'kamaz'; // $cars[] = 'kamaz'

echo '<h3>FOREACH (key => ref value)</h3>';
print_r($cars);
echo '<ul>';
foreach($cars as $i => &$car) {
    $car .= "($i)";
    //$car = $car . "($i)";
    echo "<li>$car</li>";
}
echo '</ul>';
print_r($cars);
echo '<hr>';

$a = 'b';
$b = 'c';
$c = 'd';
$d = 123;
echo '<br>'.$$$$a.' '; // => $$$b => $$c => $d => 123

$$$$a = 321;
echo $d.' '; // => 321

${$b . '123'} = 321; // c123 = 321

$func = 'count';
echo $func($cars); // count($cars)

$ar1 = ['6'=>1,2, '1' => 3,4, '10' => 5];
$ar2 = ['4'=>4, 5, 6, 7];

echo '<hr>';
$arRes = $ar1 + $ar2;
print_r($arRes);

echo '<br>';
$arRes = array_merge($ar1, $ar2);
print_r($arRes);

var_dump(array_all($ar1, fn($item)=>$item > 4)); // [0] and [1] and [2] ...
var_dump(array_any($ar1, fn($item)=>$item > 4)); // [0] or [1] or [2] ...

var_dump(array_filter($ar1, fn($value)=>$value >= 3));
var_dump(array_values($ar1));
var_dump(array_keys($ar1));

var_dump(array_map(fn($value)=> $value.' int', $ar1));
var_dump(array_diff($ar1, $ar2));

//array_first($ar1) = ar[0] = 1
//array_last($ar1) = 5

//array_key_first($ar1) = 6
//array_key_last($ar1)  = 10

array_push($ar1, '123');    // ar1 = ['6'=>1,2, '1' => 3,4, '10' => 5, '123']
$last = array_pop($ar1);    // last = '123', ar1 = ['6'=>1,2, '1' => 3,4, '10' => 5]
array_unshift($ar1, '123'); // ar1 = ['123','6'=>1,2, '1' => 3,4, '10' => 5, '123']
$first = array_shift($ar1); // first = '123', ar1 = ['6'=>1,2, '1' => 3,4, '10' => 5]