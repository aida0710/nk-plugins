<?php

/** @noinspection PhpIfWithCommonPartsInspection */
declare(strict_types = 0);

namespace czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\noise\glowstone;

use pocketmine\utils\Random;

class SimplexNoise extends PerlinNoise {

    protected const SQRT_3 = 1.7320508075688772;
    protected const F2 = 0.5 * (self::SQRT_3 - 1);
    protected const G2 = (3 - self::SQRT_3) / 6;
    protected const G22 = self::G2 * 2.0 - 1;
    protected const F3 = 1.0 / 3.0;
    protected const G3 = 1.0 / 6.0;
    protected const G32 = self::G3 * 2.0;
    protected const G33 = self::G3 * 3.0 - 1.0;

    /** @var Grad[] */
    private static array $grad3;

    /** @var int[] */
    protected array $permMod12 = [];

    /**
     * Creates a simplex noise generator.
     *
     * @param Random $rand the PRNG to use
     */
    public function __construct(Random $rand) {
        parent::__construct($rand);
        for ($i = 0; $i < 512; ++$i) {
            $this->permMod12[$i] = $this->perm[$i] % 12;
        }
    }

    public static function init() : void {
        self::$grad3 = [
            new Grad(1, 1, 0), new Grad(-1, 1, 0), new Grad(1, -1, 0),
            new Grad(-1, -1, 0),
            new Grad(1, 0, 1), new Grad(-1, 0, 1), new Grad(1, 0, -1), new Grad(-1, 0, -1),
            new Grad(0, 1, 1), new Grad(0, -1, 1), new Grad(0, 1, -1), new Grad(0, -1, -1),
        ];
    }

    public function noise3d(float $xin, float $yin = 0.0, float $zin = 0.0) : float {
        if ($yin === 0.0) {
            return parent::noise3d($xin, $yin, $zin);
        }
        $xin += $this->offsetX;
        $yin += $this->offsetY;
        if ($xin === 0.0) {
            return $this->simplex2D($xin, $yin);
        }
        $zin += $this->offsetZ;
        return $this->simplex3D($xin, $yin, $zin);
    }

    /**
     * @param float[] $noise
     * @return float[]
     */
    protected function get2dNoise(array &$noise, float $x, float $z, int $sizeX, int $sizeY, float $scaleX, float $scaleY, float $amplitude) : array {
        $index = -1;
        for ($i = 0; $i < $sizeY; ++$i) {
            $zin = $this->offsetY + ($z + $i) * $scaleY;
            for ($j = 0; $j < $sizeX; ++$j) {
                $xin = $this->offsetX + ($x + $j) * $scaleX;
                $noise[++$index] += $this->simplex2D($xin, $zin) * $amplitude;
            }
        }
        return $noise;
    }

    private function simplex2D(float $xin, float $yin) : float {
        // Skew the input space to determine which simplex cell we're in
        $s = ($xin + $yin) * self::F2; // Hairy factor for 2D
        $i = self::floor($xin + $s);
        $j = self::floor($yin + $s);
        $t = ($i + $j) * self::G2;
        $dx0 = $i - $t; // Unskew the cell origin back to (x,y) space
        $dy0 = $j - $t;
        $x0 = $xin - $dx0;          // The x,y distances from the cell origin
        $y0 = $yin - $dy0;
        // For the 2D case, the simplex shape is an equilateral triangle.
        // Determine which simplex we are in.
        // i1 - offsets for second (middle) corner of simplex in (i,j) coords
        if ($x0 > $y0) {
            $i1 = 1; // lower triangle, XY order: (0,0)->(1,0)->(1,1)
            $j1 = 0;
        } else {
            $i1 = 0; // upper triangle, YX order: (0,0)->(0,1)->(1,1)
            $j1 = 1;
        }
        // A step of (1,0) in (i,j) means a step of (1-c,-c) in (x,y), and
        // a step of (0,1) in (i,j) means a step of (-c,1-c) in (x,y), where
        // c = (3-sqrt(3))/6
        $x1 = $x0 - $i1 + self::G2; // Offsets for middle corner in (x,y) unskewed coords
        $y1 = $y0 - $j1 + self::G2;
        $x2 = $x0 + self::G22; // Offsets for last corner in (x,y) unskewed coords
        $y2 = $y0 + self::G22;
        // Work out the hashed gradient indices of the three simplex corners
        $ii = $i & 255;
        $jj = $j & 255;
        $gi0 = $this->permMod12[$ii + $this->perm[$jj]];
        $gi1 = $this->permMod12[$ii + $i1 + $this->perm[$jj + $j1]];
        $gi2 = $this->permMod12[$ii + 1 + $this->perm[$jj + 1]];
        // Calculate the contribution from the three corners
        $t0 = 0.5 - $x0 * $x0 - $y0 * $y0;
        if ($t0 < 0) {
            $n0 = 0.0;
        } else {
            $t0 *= $t0;
            $n0 = $t0 * $t0 * self::dot(self::$grad3[$gi0], $x0, $y0); // (x,y) of grad_3 used for 2D gradient
        }
        $t1 = 0.5 - $x1 * $x1 - $y1 * $y1;
        if ($t1 < 0) {
            $n1 = 0.0;
        } else {
            $t1 *= $t1;
            $n1 = $t1 * $t1 * self::dot(self::$grad3[$gi1], $x1, $y1);
        }
        $t2 = 0.5 - $x2 * $x2 - $y2 * $y2;
        if ($t2 < 0) {
            $n2 = 0.0;
        } else {
            $t2 *= $t2;
            $n2 = $t2 * $t2 * self::dot(self::$grad3[$gi2], $x2, $y2);
        }
        // Add contributions from each corner to get the final noise value.
        // The result is scaled to return values in the interval [-1,1].
        return 70.0 * ($n0 + $n1 + $n2);
    }

    public static function floor(float $x) : int {
        return $x > 0 ? (int) $x : (int) $x - 1;
    }

    protected static function dot(Grad $g, float $x, float $y, float $z = 0.0) : float {
        return $g->x * $x + $g->y * $y + $g->z * $z;
    }

    /**
     * @param float[] $noise
     * @return float[]
     */
    protected function get3dNoise(array &$noise, float $x, float $y, float $z, int $sizeX, int $sizeY, int $sizeZ, float $scaleX, float $scaleY, float $scaleZ, float $amplitude) : array {
        $index = -1;
        for ($i = 0; $i < $sizeZ; ++$i) {
            $zin = $this->offsetZ + ($z + $i) * $scaleZ;
            for ($j = 0; $j < $sizeX; ++$j) {
                $xin = $this->offsetX + ($x + $j) * $scaleX;
                for ($k = 0; $k < $sizeY; ++$k) {
                    $yin = $this->offsetY + ($y + $k) * $scaleY;
                    $noise[++$index] += $this->simplex3D($xin, $yin, $zin) * $amplitude;
                }
            }
        }
        return $noise;
    }

    private function simplex3D(float $xin, float $yin, float $zin) : float {
        // Skew the input space to determine which simplex cell we're in
        $s = ($xin + $yin + $zin) * self::F3; // Very nice and simple skew factor for 3D
        $i = self::floor($xin + $s);
        $j = self::floor($yin + $s);
        $k = self::floor($zin + $s);
        $t = ($i + $j + $k) * self::G3;
        $dx0 = $i - $t;             // Unskew the cell origin back to (x,y,z) space
        $dy0 = $j - $t;
        $dz0 = $k - $t;
        // For the 3D case, the simplex shape is a slightly irregular tetrahedron.
        // i1 - Offsets for second corner of simplex in (i,j,k) coords
        // i2 - Offsets for third corner of simplex in (i,j,k) coords
        $x0 = $xin - $dx0;          // The x,y,z distances from the cell origin
        $y0 = $yin - $dy0;
        $z0 = $zin - $dz0;
        // Determine which simplex we are in
        if ($x0 >= $y0) {
            if ($y0 >= $z0) {
                $i1 = 1; // X Y Z order
                $j1 = 0;
                $k1 = 0;
                $i2 = 1;
                $j2 = 1;
                $k2 = 0;
            } elseif ($x0 >= $z0) {
                $i1 = 1; // X Z Y order
                $j1 = 0;
                $k1 = 0;
                $i2 = 1;
                $j2 = 0;
                $k2 = 1;
            } else {
                $i1 = 0; // Z X Y order
                $j1 = 0;
                $k1 = 1;
                $i2 = 1;
                $j2 = 0;
                $k2 = 1;
            }
        } else { // x0<y0
            if ($y0 < $z0) {
                $i1 = 0; // Z Y X order
                $j1 = 0;
                $k1 = 1;
                $i2 = 0;
                $j2 = 1;
                $k2 = 1;
            } elseif ($x0 < $z0) {
                $i1 = 0; // Y Z X order
                $j1 = 1;
                $k1 = 0;
                $i2 = 0;
                $j2 = 1;
                $k2 = 1;
            } else {
                $i1 = 0; // Y X Z order
                $j1 = 1;
                $k1 = 0;
                $i2 = 1;
                $j2 = 1;
                $k2 = 0;
            }
        }
        // A step of (1,0,0) in (i,j,k) means a step of (1-c,-c,-c) in (x,y,z),
        // a step of (0,1,0) in (i,j,k) means a step of (-c,1-c,-c) in (x,y,z), and
        // a step of (0,0,1) in (i,j,k) means a step of (-c,-c,1-c) in (x,y,z), where
        // c = 1/6.
        $x1 = $x0 - $i1 + self::G3; // Offsets for second corner in (x,y,z) coords
        $y1 = $y0 - $j1 + self::G3;
        $z1 = $z0 - $k1 + self::G3;
        $x2 = $x0 - $i2 + self::G32; // Offsets for third corner in (x,y,z) coords
        $y2 = $y0 - $j2 + self::G32;
        $z2 = $z0 - $k2 + self::G32;
        // Work out the hashed gradient indices of the four simplex corners
        $ii = $i & 255;
        $jj = $j & 255;
        $kk = $k & 255;
        $gi0 = $this->permMod12[$ii + $this->perm[$jj + $this->perm[$kk]]];
        $gi1 = $this->permMod12[$ii + $i1 + $this->perm[$jj + $j1 + $this->perm[$kk + $k1]]];
        $gi2 = $this->permMod12[$ii + $i2 + $this->perm[$jj + $j2 + $this->perm[$kk + $k2]]];
        $gi3 = $this->permMod12[$ii + 1 + $this->perm[$jj + 1 + $this->perm[$kk + 1]]];
        // Calculate the contribution from the four corners
        $t0 = 0.5 - $x0 * $x0 - $y0 * $y0 - $z0 * $z0;
        // n0 - Noise contributions from the four corners
        if ($t0 < 0) {
            $n0 = 0.0;
        } else {
            $t0 *= $t0;
            $n0 = $t0 * $t0 * self::dot(self::$grad3[$gi0], $x0, $y0, $z0);
        }
        $t1 = 0.5 - $x1 * $x1 - $y1 * $y1 - $z1 * $z1;
        if ($t1 < 0) {
            $n1 = 0.0;
        } else {
            $t1 *= $t1;
            $n1 = $t1 * $t1 * self::dot(self::$grad3[$gi1], $x1, $y1, $z1);
        }
        $t2 = 0.5 - $x2 * $x2 - $y2 * $y2 - $z2 * $z2;
        if ($t2 < 0) {
            $n2 = 0.0;
        } else {
            $t2 *= $t2;
            $n2 = $t2 * $t2 * self::dot(self::$grad3[$gi2], $x2, $y2, $z2);
        }
        $x3 = $x0 + self::G33; // Offsets for last corner in (x,y,z) coords
        $y3 = $y0 + self::G33;
        $z3 = $z0 + self::G33;
        $t3 = 0.5 - $x3 * $x3 - $y3 * $y3 - $z3 * $z3;
        if ($t3 < 0) {
            $n3 = 0.0;
        } else {
            $t3 *= $t3;
            $n3 = $t3 * $t3 * self::dot(self::$grad3[$gi3], $x3, $y3, $z3);
        }
        // Add contributions from each corner to get the final noise value.
        // The result is scaled to stay just inside [-1,1]
        return 32.0 * ($n0 + $n1 + $n2 + $n3);
    }
}

SimplexNoise::init();
