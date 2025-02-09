<?php

declare(strict_types = 0);

namespace czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator;

use InvalidArgumentException;
use function strtolower;

final class Environment {

    public const OVERWORLD = 0;
    public const NETHER = -1;
    public const THE_END = 1;

    private function __construct() {
    }

    public static function fromString(string $string) : int {
        return match (strtolower($string)) {
            'overworld' => self::OVERWORLD,
            'nether' => self::NETHER,
            'end', 'the_end' => self::THE_END,
            default => throw new InvalidArgumentException("Could not convert string \"$string\" to a " . self::class . ' constant')
        };
    }
}
