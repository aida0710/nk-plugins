<?php

abstract class Tutorial {

    abstract public function __construct();

    abstract public function getName(): string;

    abstract function setProgress(int $progress): void;

    abstract function getProgress(): int;

    abstract function setValue(mixed $value): void;

    abstract public function getValue(): mixed;

    public function getFallbackValue(): mixed;

}