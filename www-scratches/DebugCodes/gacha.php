<?php

class gacha {

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
$class = new gacha();
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
}