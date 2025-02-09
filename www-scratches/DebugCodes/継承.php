<?php

declare(strict_types = 1);

interface Human {

    public function height() : int;

    public function weight() : int;

    public function iq() : int;

    public function name() : string;

    public function age() : int;

    public function isAlive() : bool;

}

class Spiny implements Human {

    public function age() : int {
        return 16;
    }

    public function height() : int {
        return 120;
    }

    public function iq() : int {
        return 100;
    }

    public function isAlive() : bool {
        return false;
    }

    public function name() : string {
        return 'Spiny';
    }

    public function weight() : int {
        return 70;
    }

}

class WeakeningSpiny extends Spiny {

    public function iq() : int {
        $iq = parent::iq();
        return $iq - 10;
    }

    public function isAlive() : bool {
        return true;
    }

    public function name() : string {
        $name = parent::name();
        return '弱弱しい' . $name;
    }

}

echo (new WeakeningSpiny())->name();
echo (bool) (new WeakeningSpiny())->isAlive();
