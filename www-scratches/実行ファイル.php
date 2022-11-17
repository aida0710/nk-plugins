<?php

class Main {

    private const A = 100;
    private int $PI = 0;

    public function __construct() {
        for ($i = 0; $i < self::A; $i++) {
            if ($i % 2 == 0) {
                $b = -1;
            } else {
                $b = 1;
            }
            $this->PI += (4 / (2 * $i - 1)) * $b;
            sleep(1);
            echo($this->PI);
        }
    }
}

/*A=100
pi=0
forL in range(1,A):
ifL%2==0:
B=-1
else:
B=1
pi+=(4/(2*L-1))*B
print(pi)*/
$class = new Main();
var_dump($class);
