<?php

declare(strict_types=1);

$test = bcadd(1.7, 0.3, 2);
var_dump($test);
$test = bcadd($test, 5.9, 2);
var_dump($test);
echo $test;
