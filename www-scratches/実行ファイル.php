<?php
$x = 2;
$y = 2;
add($x, $y);

var_dump($x);
var_dump($y);

function add($x, &$y): void {
    $x = $x + $y;
    $y = $x + $y;
}