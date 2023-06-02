<?php

declare(strict_types = 0);

namespace lazyperson0710\PlayerSetting\object\settings\normal;

use lazyperson0710\PlayerSetting\object\Setting;

class LevelUpDisplaySetting extends Setting {

    protected string $value;

    public function getFallbackValue() : string {
        return 'title';
    }

    public static function getName() : string {
        return 'LevelUpDisplay';
    }

    public function getValue() : string {
        return $this->value ?? $this->getFallbackValue();
    }

    /**
     * 入力可能な値
     * #title
     * #toast
     * #none
     */
    public function setValue(mixed $value) : void {
        $this->value = $value;
    }
}
