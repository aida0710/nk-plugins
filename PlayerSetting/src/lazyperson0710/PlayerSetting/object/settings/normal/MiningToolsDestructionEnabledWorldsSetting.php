<?php

declare(strict_types = 0);

namespace lazyperson0710\PlayerSetting\object\settings\normal;

use lazyperson0710\PlayerSetting\object\Setting;

class MiningToolsDestructionEnabledWorldsSetting extends Setting {

    protected string $value;

    public static function getName() : string {
        return 'MiningToolsDestructionEnabledWorlds';
    }

    public function getValue() : string {
        return $this->value ?? $this->getFallbackValue();
    }

    /**
     * 入力可能な値
     * #all
     * #life
     * #nature
     * #none
     */
    public function setValue(mixed $value) : void {
        $this->value = $value;
    }

    public function getFallbackValue() : string {
        return 'all';
    }
}
