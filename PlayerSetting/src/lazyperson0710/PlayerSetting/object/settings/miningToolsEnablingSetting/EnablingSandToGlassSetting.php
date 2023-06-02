<?php

declare(strict_types = 0);

namespace lazyperson0710\PlayerSetting\object\settings\miningToolsEnablingSetting;

use lazyperson0710\PlayerSetting\object\Setting;

class EnablingSandToGlassSetting extends Setting {

    protected bool $value;

    public function getFallbackValue() : bool {
        return false;
    }

    public static function getName() : string {
        return 'EnablingSandToGlass';
    }

    public function getValue() : bool {
        return $this->value ?? $this->getFallbackValue();
    }

    public function setValue(mixed $value) : void {
        $this->value = $value;
    }
}
