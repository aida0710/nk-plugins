<?php

declare(strict_types = 1);
namespace lazyperson0710\PlayerSetting\object;

abstract class Setting {

    final public function __construct() {
    }

    abstract public static function getName(): string;

    abstract public function setValue(mixed $value): void;

    abstract public function getValue(): mixed;

    abstract public function getFallbackValue(): mixed;
}