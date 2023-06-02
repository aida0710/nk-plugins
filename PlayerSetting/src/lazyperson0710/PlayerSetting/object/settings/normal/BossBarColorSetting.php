<?php

declare(strict_types = 0);

namespace lazyperson0710\PlayerSetting\object\settings\normal;

use lazyperson0710\PlayerSetting\object\Setting;
use pocketmine\network\mcpe\protocol\types\BossBarColor;

class BossBarColorSetting extends Setting {

    protected int $value;

    public static function getName() : string {
        return 'BossBarColor';
    }

    public function getValue() : int {
        return $this->value ?? $this->getFallbackValue();
    }

    public function setValue(mixed $value) : void {
        $this->value = $value;
    }

    public function getFallbackValue() : int {
        return BossBarColor::PINK;
    }
}
