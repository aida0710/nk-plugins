<?php

new Main();

class Main {

    public function __construct() {
        $this->matchTest();
    }

    public function matchTest(): void {
        $class = [
            new Hoge_1(),
            new Hoge_2(),
            new Hoge_3(),
        ];
        foreach ($class as $value) {
            $msg = match ($value::class) {
                Hoge_1::class => "::class_1",
                Hoge_2::class => "::class_2",
                Hoge_3::class => "::class_3",
            };
            $instanceof = match (true) {
                $value instanceof Hoge_1 => "instanceof_1",
                $value instanceof Hoge_2 => "instanceof_2",
                $value instanceof Hoge_3 => "instanceof_3",
            };
            echo $msg . "\n";
            echo $instanceof . "\n";
        }
    }
}

class Hoge_1 {

}

class Hoge_2 {

}

class Hoge_3 {

}