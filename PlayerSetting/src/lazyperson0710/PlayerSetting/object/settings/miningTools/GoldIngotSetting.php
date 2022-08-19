<?php

declare(strict_types = 1);
namespace lazyperson0710\PlayerSetting\object\settings\miningTools;

use lazyperson0710\PlayerSetting\object\Setting;

class GoldIngotSetting extends Setting {

    protected bool $value;

    public static function getName(): string {
        return 'GoldIngot';
    }

    public function setValue(mixed $value): void {
        $this->value = $value;
    }

    public function getValue(): bool {
        return $this->value ?? $this->getFallbackValue();
    }

    public function getFallbackValue(): bool {
        return false;
    }
}