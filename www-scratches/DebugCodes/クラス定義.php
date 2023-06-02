<?php

declare(strict_types = 1);

class Player {

    private string $name;
    private int $age;

    public function __construct(string $name, int $age) {
        $this->name = $name;
        $this->age = $age;
        if ($this->age < 0) {
            $this->age = 0;
            //throw new Exception("Age must be greater than 0");
        }
    }

    public function getName() : string {
        return $this->name;
    }

    public function getAge() : int {
        return $this->age;
    }
}

function test(string $name, int $age) : Player {
    $player = new Player($name, $age);
    echo $player->getName() . ' is ' . $player->getAge() . ' years old.';
    return $player;
}

$player = test('lazyperson710', -10);
