<?php

declare(strict_types = 0);

namespace czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\noise\bukkit;

class PerlinNoiseGenerator extends BasePerlinNoiseGenerator {

    private static ?PerlinNoiseGenerator $instance;

    public static function getNoise3d(float $x, float $y = 0.0, float $z = 0.0) : float {
        return self::getInstance()->noise3d($x, $y, $z);
    }

    public function noise3d(float $x, float $y = 0.0, float $z = 0.0) : float {
        $x += $this->offsetX;
        $y += $this->offsetY;
        $z += $this->offsetZ;
        $floorX = self::floor($x);
        $floorY = self::floor($y);
        $floorZ = self::floor($z);
        // Find unit cube containing the point
        $X = $floorX & 255;
        $Y = $floorY & 255;
        $Z = $floorZ & 255;
        // Get relative xyz coordinates of the point within the cube
        $x -= $floorX;
        $y -= $floorY;
        $z -= $floorZ;
        // Compute fade curves for xyz
        $fX = self::fade($x);
        $fY = self::fade($y);
        $fZ = self::fade($z);
        // Hash coordinates of the cube corners
        $A = $this->perm[$X] + $Y;
        $AA = $this->perm[$A] + $Z;
        $AB = $this->perm[$A + 1] + $Z;
        $B = $this->perm[$X + 1] + $Y;
        $BA = $this->perm[$B] + $Z;
        $BB = $this->perm[$B + 1] + $Z;
        return self::lerp($fZ, self::lerp($fY, self::lerp($fX, self::grad($this->perm[$AA], $x, $y, $z),
            self::grad($this->perm[$BA], $x - 1, $y, $z)),
            self::lerp($fX, self::grad($this->perm[$AB], $x, $y - 1, $z),
                self::grad($this->perm[$BB], $x - 1, $y - 1, $z))),
            self::lerp($fY, self::lerp($fX, self::grad($this->perm[$AA + 1], $x, $y, $z - 1),
                self::grad($this->perm[$BA + 1], $x - 1, $y, $z - 1)),
                self::lerp($fX, self::grad($this->perm[$AB + 1], $x, $y - 1, $z - 1),
                    self::grad($this->perm[$BB + 1], $x - 1, $y - 1, $z - 1))));
    }

    /**
     * Gets the singleton unseeded instance of this generator
     */
    public static function getInstance() : PerlinNoiseGenerator {
        return self::$instance ??= new PerlinNoiseGenerator();
    }

    /**
     * Generates noise for the 3D coordinates using the specified number of
     * octaves and parameters
     *
     * @param float $x         X-coordinate
     * @param float $y         Y-coordinate
     * @param float $z         Z-coordinate
     * @param int   $octaves   Number of octaves to use
     * @param float $frequency How much to alter the frequency by each octave
     * @param float $amplitude How much to alter the amplitude by each octave
     * @return float resulting noise
     */
    public static function getNoise(float $x, float $y, float $z, int $octaves, float $frequency, float $amplitude) : float {
        return self::getInstance()->noise($x, $y, $z, $octaves, $frequency, $amplitude);
    }

    /**
     * Generates noise for the 3D coordinates using the specified number of
     * octaves and parameters
     *
     * @param float $x          X-coordinate
     * @param float $y          Y-coordinate
     * @param float $z          Z-coordinate
     * @param int   $octaves    Number of octaves to use
     * @param float $frequency  How much to alter the frequency by each octave
     * @param float $amplitude  How much to alter the amplitude by each octave
     * @param bool  $normalized If true, normalize the value to [-1, 1]
     * @return float Resulting noise
     */
    public function noise(float $x, float $y, float $z, int $octaves, float $frequency, float $amplitude, bool $normalized = false) : float {
        $result = 0.0;
        $amp = 1.0;
        $freq = 1.0;
        $max = 0.0;
        for ($i = 0; $i < $octaves; ++$i) {
            $result += $this->noise3d($x * $freq, $y * $freq, $z * $freq) * $amp;
            $max += $amp;
            $freq *= $frequency;
            $amp *= $amplitude;
        }
        if ($normalized) {
            $result /= $max;
        }
        return $result;
    }
}
