<?php

declare(strict_types = 1);
namespace lazyperson0710\PlayerSetting\object\settings\normal;

use lazyperson0710\PlayerSetting\object\Setting;

class MiningToolsDestructionEnabledWorldsSetting extends Setting {

    protected string $value;

    public static function getName(): string {
        return 'MiningToolsDestructionEnabledWorlds';
    }

    /**
     * 入力可能な値
     * #all
     * #life
     * #nature
     * #none
     */
    public function setValue(mixed $value): void {
        $this->value = $value;
    }

    public function getValue(): string {
        return $this->value ?? $this->getFallbackValue();
    }

    public function getFallbackValue(): string {
        return "all";
    }
}