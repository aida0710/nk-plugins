<?php

$value = [
    1,
    10,
    1.1,
    1.0,
    "1",
    "10",
    "1.0",
    "1.1",
    "4f",
    "aaaa",
];
foreach ($value as $v) {
    if (is_numeric($v)) {
        $v = (int)$v;
        var_dump("num" . $v);
    }
    //if (!is_int($v)) {
    //    var_dump($v);
    //}
    //var_dump($v);
}