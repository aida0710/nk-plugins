<?php

declare(strict_types=1);
namespace lazyperson0710\PlayerSetting\object\settings\donation;

use lazyperson0710\PlayerSetting\object\Setting;

class Donation_1500 extends Setting {

	protected bool $value;

	public static function getName() : string {
		return 'donation_1500';
	}

	public function setValue(mixed $value) : void {
		$this->value = $value;
	}

	public function getValue() : bool {
		return $this->value ?? $this->getFallbackValue();
	}

	public function getFallbackValue() : bool {
		return false;
	}
}
