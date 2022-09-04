<?php
/*interface Human {

    public function height(): int;

    public function weight(): int;

    public function iq(): int;

    public function name(): string;

    public function age(): int;

    public function isAlive(): bool;

}

class Spiny implements Human {

    public function height(): int {
        return 120;
    }

    public function weight(): int {
        return 70;
    }

    public function iq(): int {
        return 100;
    }

    public function name(): string {
        return "Spiny";
    }

    public function age(): int {
        return 16;
    }

    public function isAlive(): bool {
        return false;
    }

}

class WeakeningSpiny extends Spiny {
    public function name(): string {
        $name = parent::name();
        return "弱弱しい". $name;
    }
    public function iq(): int {
        $iq = parent::iq();
        return $iq - 10;
    }

    public function isAlive(): bool {
        return true;
    }

}

echo (new WeakeningSpiny())->name();
echo (bool)(new WeakeningSpiny())->isAlive();*/
/*$test = bcadd(1.7, 0.3, 2);
var_dump($test);
$test = bcadd($test, 5.9,2);
var_dump($test);

echo $test;*/
/*class test {

    private array $tmp_table;
    private array $rank;
    protected int $num;

    public function __construct() {
        $this->tmp_table = $this->getTmpTable();
        $this->initTable();
    }

    public function initTable(): void {
        $this->rank = array_map(static fn($value) => $value * 1000, $this->tmp_table);
        $this->num = (int)array_sum($this->rank);
    }

    public function run($num = 1): array {
        $result = [];
        for ($i = 1; $i <= $num; $i++) {
            $rand = mt_rand(1, $this->num);
            $count = 0;
            foreach ($this->rank as $table => $item) {
                $count += $item;
                if ($rand <= $count) {
                    $result[$table] ??= 0;
                    ++$result[$table];
                    continue 2;
                }
            }
            throw new \RuntimeException("test");
        }
        return $result;
    }

    protected function getTmpTable(): array {
        return [
            "C" => 80,
            "UC" => 13,
            "R" => 5,
            "m" => 0.001,
            "SR" => 1.7,
            "L" => 0.3,
        ];
    }
}

//$count = 1000010;
$count = 1000010000;
$class = new test();
$result = $class->run($count);
$result += [
    "C" => 0,
    "UC" => 0,
    "R" => 0,
    "SR" => 0,
    "L" => 0,
    "m" => 0,
];
const SCALE = 10;
foreach ($result as $table => $item) {
    //bcmul(bcdiv($item,$count,SCALE), 100,SCALE)
    echo "\n{$table}の数{$item},	", $item / $count * 100, "%";
}*/
/*class Main {

    private string $name;

    public function __construct(string $name) {
        if (mt_rand(0, 1) === 0) {
            $this->name = $name;
        }
    }

    public function getName(): string {
        if (!isset($this->name)) {
            return "メンバ変数が定義されてないよ！";
        }
        return $this->name;
    }
}

echo (new Main("0が選ばれました"))->getName();*/
/*$array = [
    "A",
    "B",
    "C",
    "D",
    "E",
];

foreach ($array as $key => $value) echo $key, ":", $value, "\n";*/
/*foreach (["a", "b", "c", "d", "e"] as $key => $value) {
    if ($key <= 2) {
        for ($i = 0; $i < 3; $i++) {
            while ($i < 5) {
                echo $value, $i++, "\n";
            }
        }
    }
}*/