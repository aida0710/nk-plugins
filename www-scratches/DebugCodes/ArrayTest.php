<?php

declare(strict_types=1);
$array = [
	"a",
	"b",
	"c",
	"d",
	"e",
	"f",
	"g",
];
//配列の要素を数で指定して切り捨てる
//$test = array_slice($array, 0, 4);
var_dump([1, 2, ...$array, 3, 7]);
