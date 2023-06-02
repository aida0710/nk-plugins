<?php

declare(strict_types = 0);

namespace lazyperson0710\PlayerSetting\object\settings\normal;

use lazyperson0710\PlayerSetting\object\Setting;

class CoordinateSetting extends Setting {

    protected bool $value;

    public static function getName() : string {
        return 'Coordinate';
    }

    public function getValue() : bool {
        return $this->value ?? $this->getFallbackValue();
    }

    public function setValue(mixed $value) : void {
        $this->value = $value;
    }

    public function getFallbackValue() : bool {
        return true;
    }
}
