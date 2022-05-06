<?php

class Main {

    private function rand(): void {
        $result = null;
        for ($j = 1; $j <= 1000; $j++) {
            $time_start = microtime(true);
            try {
                for ($i = 0; $i <= 1000000; $i++) {
                    //$rand = rand(1, 1000);
                    //$rand = mt_rand(1, 1000);
                    $rand = random_int(1, 1000);
                    //var_dump($i . " - " . $rand);
                }
            } catch (Exception $e) {
                return;
            }
            $time = microtime(true) - $time_start;
            $time = substr($time, 0, 9);
            $result[] = $time;
            echo "{$j}回目 - {$time} 秒\n";
        }
        $result = array_sum($result);
        $result = $result / $j;
        $result = substr($result, 0, 9);
        echo $result;
    }
}