<?php

declare(strict_types = 0);

namespace lazyperson0710\PlayerSetting\object\settings\normal;

use lazyperson0710\PlayerSetting\object\Setting;

class PayCommandUseSetting extends Setting {

    protected bool $value;

    public function getFallbackValue() : bool {
        return true;
    }

    public static function getName() : string {
        return 'PayCommandUse';
    }

    public function getValue() : bool {
        return $this->value ?? $this->getFallbackValue();
    }

    public function setValue(mixed $value) : void {
        $this->value = $value;
    }
}
