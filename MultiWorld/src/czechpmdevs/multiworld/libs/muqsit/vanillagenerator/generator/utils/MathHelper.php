<?php

declare(strict_types = 0);

namespace czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\utils;

use function sin;
use const M_PI;

class MathHelper {

    private static MathHelper $instance;

    /** @var float[] */
    private array $sinTable = [];

    private function __construct() {
        for ($i = 0; $i < 65536; ++$i) {
            $this->sinTable[$i] = sin((float) $i * M_PI * 2.0 / 65536.0);
        }
    }

    public static function getInstance() : MathHelper {
        return MathHelper::$instance ?? MathHelper::$instance = new MathHelper();
    }

    public function sin(float $num) : float {
        return $this->sinTable[(int) ($num * 10430.378) & 0xffff];
    }

    public function cos(float $num) : float {
        return $this->sinTable[(int) ($num * 10430.378 + 16384.0) & 0xffff];
    }
}
