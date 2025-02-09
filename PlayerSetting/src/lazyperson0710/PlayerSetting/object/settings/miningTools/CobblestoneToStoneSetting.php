<?php

declare(strict_types = 0);

namespace lazyperson0710\PlayerSetting\object\settings\miningTools;

use lazyperson0710\PlayerSetting\object\Setting;

class CobblestoneToStoneSetting extends Setting {

    protected bool $value;

    public static function getName() : string {
        return 'CobblestoneToStone';
    }

    public function getValue() : bool {
        return $this->value ?? $this->getFallbackValue();
    }

    public function setValue(mixed $value) : void {
        $this->value = $value;
    }

    public function getFallbackValue() : bool {
        return false;
    }
}
