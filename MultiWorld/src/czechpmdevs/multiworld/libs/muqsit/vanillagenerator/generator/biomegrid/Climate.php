<?php

declare(strict_types = 1);
namespace czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\biomegrid;

class Climate {

    public int $value;

    /** @var int[] */
    public array $cross_types;

    public int $final_value;

    /**
     * @param int $value
     * @param int[] $cross_types
     * @param int $final_value
     */
    public function __construct(int $value, array $cross_types, int $final_value) {
        $this->value = $value;
        $this->cross_types = $cross_types;
        $this->final_value = $final_value;
    }
}