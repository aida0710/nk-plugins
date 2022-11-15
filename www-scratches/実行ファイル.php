<?php

class a{
    public function __construct(){
        $class = "aaa";
        var_dump($class);
    }

    public function b(){
        $class = "bbb";
        var_dump($class);
    }
}

$class = new a();
var_dump($class);
