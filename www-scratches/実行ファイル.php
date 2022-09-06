<?php
/*
$array = [
    "a",
    "b",
    "c",
    "d",
    "e",
    "f",
    "g",
];

$fun = function (string $a, string $b, string $c, string $d, string $e, string $f, string $g) {
    var_dump($a);
    var_dump($b);
    var_dump($c);
    var_dump($d);
    var_dump($e);
    var_dump($f);
    var_dump($g);
};

$fun(...$array);*/

/*$array = [
    "a",
    "b",
    "c",
    "d",
    "e",
    "f",
    "g",
];

(function(string $a, string $b, string $c, string $d, string $e, string $f, string $g) {
    echo $a;
    echo $b;
    echo $c;
    echo $d;
    echo $e;
    echo $f;
    echo $g;
})(...$array);*/
$test = "aaa";
function test(): void {
    //global $test;
    var_dump($test);
}

test();
var_dump($test);