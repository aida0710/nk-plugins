<?php

/** @noinspection PhpIfWithCommonPartsInspection */
declare(strict_types = 0);

namespace czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\noise\bukkit;

use pocketmine\utils\Random;

class SimplexNoiseGenerator extends BasePerlinNoiseGenerator {

    protected const SQRT_3 = 3 ** 0.5;
    protected const SQRT_5 = 5 ** 0.5;
    protected const F2 = 0.5 * (self::SQRT_3 - 1);
    protected const G2 = (3 - self::SQRT_3) / 6;
    protected const G22 = self::G2 * 2.0 - 1;
    protected const F3 = 1.0 / 3.0;
    protected const G3 = 1.0 / 6.0;
    protected const F4 = (self::SQRT_5 - 1.0) / 4.0;
    protected const G4 = (5.0 - self::SQRT_5) / 20.0;
    protected const G42 = self::G4 * 2.0;
    protected const G43 = self::G4 * 3.0;
    protected const G44 = self::G4 * 4.0 - 1.0;

    protected const GRAD4 = [
        [0, 1, 1, 1], [0, 1, 1, -1], [0, 1, -1, 1], [0, 1, -1, -1],
        [0, -1, 1, 1], [0, -1, 1, -1], [0, -1, -1, 1], [0, -1, -1, -1],
        [1, 0, 1, 1], [1, 0, 1, -1], [1, 0, -1, 1], [1, 0, -1, -1],
        [-1, 0, 1, 1], [-1, 0, 1, -1], [-1, 0, -1, 1], [-1, 0, -1, -1],
        [1, 1, 0, 1], [1, 1, 0, -1], [1, -1, 0, 1], [1, -1, 0, -1],
        [-1, 1, 0, 1], [-1, 1, 0, -1], [-1, -1, 0, 1], [-1, -1, 0, -1],
        [1, 1, 1, 0], [1, 1, -1, 0], [1, -1, 1, 0], [1, -1, -1, 0],
        [-1, 1, 1, 0], [-1, 1, -1, 0], [-1, -1, 1, 0], [-1, -1, -1, 0],
    ];

    protected const SIMPLEX = [
        [0, 1, 2, 3], [0, 1, 3, 2], [0, 0, 0, 0], [0, 2, 3, 1], [0, 0, 0, 0], [0, 0, 0, 0], [0, 0, 0, 0], [1, 2, 3, 0],
        [0, 2, 1, 3], [0, 0, 0, 0], [0, 3, 1, 2], [0, 3, 2, 1], [0, 0, 0, 0], [0, 0, 0, 0], [0, 0, 0, 0], [1, 3, 2, 0],
        [0, 0, 0, 0], [0, 0, 0, 0], [0, 0, 0, 0], [0, 0, 0, 0], [0, 0, 0, 0], [0, 0, 0, 0], [0, 0, 0, 0], [0, 0, 0, 0],
        [1, 2, 0, 3], [0, 0, 0, 0], [1, 3, 0, 2], [0, 0, 0, 0], [0, 0, 0, 0], [0, 0, 0, 0], [2, 3, 0, 1], [2, 3, 1, 0],
        [1, 0, 2, 3], [1, 0, 3, 2], [0, 0, 0, 0], [0, 0, 0, 0], [0, 0, 0, 0], [2, 0, 3, 1], [0, 0, 0, 0], [2, 1, 3, 0],
        [0, 0, 0, 0], [0, 0, 0, 0], [0, 0, 0, 0], [0, 0, 0, 0], [0, 0, 0, 0], [0, 0, 0, 0], [0, 0, 0, 0], [0, 0, 0, 0],
        [2, 0, 1, 3], [0, 0, 0, 0], [0, 0, 0, 0], [0, 0, 0, 0], [3, 0, 1, 2], [3, 0, 2, 1], [0, 0, 0, 0], [3, 1, 2, 0],
        [2, 1, 0, 3], [0, 0, 0, 0], [0, 0, 0, 0], [0, 0, 0, 0], [3, 1, 0, 2], [0, 0, 0, 0], [3, 2, 0, 1], [3, 2, 1, 0],
    ];

    protected static float $offsetW;
    private static ?SimplexNoiseGenerator $instance;

    public function __construct(?Random $rand = null) {
        parent::__construct($rand);
        if ($rand !== null) {
            self::$offsetW = $rand->nextFloat() * 256;
        }
    }

    /**
     * Computes and returns the 3D unseeded simplex noise for the given
     * coordinates in 3D space
     *
     * @param float $xin X coordinate
     * @param float $yin Y coordinate
     * @param float $zin Z coordinate
     * @return float noise at given location, from range -1 to 1
     */
    public static function getNoise3d(float $xin, float $yin = 0.0, float $zin = 0.0) : float {
        return self::getInstance()->noise3d($xin, $yin, $zin);
    }

    public function noise3d(float $xin, float $yin = 0.0, float $zin = 0.0) : float {
        if ($zin === 0.0) {
            $xin += $this->offsetX;
            $yin += $this->offsetY;
            // n0, n1, n2 - Noise contributions from the three corners
            // Skew the input space to determine which simplex cell we're in
            $s = ($xin + $yin) * self::F2; // Hairy factor for 2D
            $i = self::floor($xin + $s);
            $j = self::floor($yin + $s);
            $t = ($i + $j) * self::G2;
            $X0 = $i - $t; // Unskew the cell origin back to (x,y) space
            $Y0 = $j - $t;
            $x0 = $xin - $X0;           // The x,y distances from the cell origin
            $y0 = $yin - $Y0;
            // For the 2D case, the simplex shape is an equilateral triangle.
            // Determine which simplex we are in.
            // i1, j2 - Offsets for second (middle) corner of simplex in (i,j) coords
            if ($x0 > $y0) { // lower triangle, XY order: (0,0)->(1,0)->(1,1)
                $i1 = 1;
                $j1 = 0;
            } else { // upper triangle, YX order: (0,0)->(0,1)->(1,1)
                $i1 = 0;
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
            $gi0 = $this->perm[$ii + $this->perm[$jj]] % 12;
            $gi1 = $this->perm[$ii + $i1 + $this->perm[$jj + $j1]] % 12;
            $gi2 = $this->perm[$ii + 1 + $this->perm[$jj + 1]] % 12;
            // Calculate the contribution from the three corners
            $t0 = 0.5 - $x0 * $x0 - $y0 * $y0;
            if ($t0 < 0) {
                $n0 = 0.0;
            } else {
                $t0 *= $t0;
                $n0 = $t0 * $t0 * self::dot(self::GRAD3[$gi0], $x0, $y0); // (x,y) of grad3 used for 2D gradient
            }
            $t1 = 0.5 - $x1 * $x1 - $y1 * $y1;
            if ($t1 < 0) {
                $n1 = 0.0;
            } else {
                $t1 *= $t1;
                $n1 = $t1 * $t1 * self::dot(self::GRAD3[$gi1], $x1, $y1);
            }
            $t2 = 0.5 - $x2 * $x2 - $y2 * $y2;
            if ($t2 < 0) {
                $n2 = 0.0;
            } else {
                $t2 *= $t2;
                $n2 = $t2 * $t2 * self::dot(self::GRAD3[$gi2], $x2, $y2);
            }
            // Add contributions from each corner to get the final noise value.
            // The result is scaled to return values in the interval [-1,1].
            return 70.0 * ($n0 + $n1 + $n2);
        }
        $xin += $this->offsetX;
        $yin += $this->offsetY;
        $zin += $this->offsetZ;
        // n0, n1, n2, n3 - Noise contributions from the four corners
        // Skew the input space to determine which simplex cell we're in
        $s = ($xin + $yin + $zin) * self::F3; // Very nice and simple skew factor for 3D
        $i = self::floor($xin + $s);
        $j = self::floor($yin + $s);
        $k = self::floor($zin + $s);
        $t = ($i + $j + $k) * self::G3;
        $X0 = $i - $t; // Unskew the cell origin back to (x,y,z) space
        $Y0 = $j - $t;
        $Z0 = $k - $t;
        $x0 = $xin - $X0;           // The x,y,z distances from the cell origin
        $y0 = $yin - $Y0;
        $z0 = $zin - $Z0;
        // For the 3D case, the simplex shape is a slightly irregular tetrahedron.
        // Determine which simplex we are in.
        // i1, j1, k1 - Offsets for second corner of simplex in (i,j,k) coords
        // i2, j2, k2 - Offsets for third corner of simplex in (i,j,k) coords
        if ($x0 >= $y0) {
            if ($y0 >= $z0) { // X Y Z order
                $i1 = 1;
                $j1 = 0;
                $k1 = 0;
                $i2 = 1;
                $j2 = 1;
                $k2 = 0;
            } elseif ($x0 >= $z0) { // X Z Y order
                $i1 = 1;
                $j1 = 0;
                $k1 = 0;
                $i2 = 1;
                $j2 = 0;
                $k2 = 1;
            } else { // Z X Y order
                $i1 = 0;
                $j1 = 0;
                $k1 = 1;
                $i2 = 1;
                $j2 = 0;
                $k2 = 1;
            }
        } else { // x0<y0
            if ($y0 < $z0) { // Z Y X order
                $i1 = 0;
                $j1 = 0;
                $k1 = 1;
                $i2 = 0;
                $j2 = 1;
                $k2 = 1;
            } elseif ($x0 < $z0) { // Y Z X order
                $i1 = 0;
                $j1 = 1;
                $k1 = 0;
                $i2 = 0;
                $j2 = 1;
                $k2 = 1;
            } else { // Y X Z order
                $i1 = 0;
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
        $x2 = $x0 - $i2 + 2.0 * self::G3; // Offsets for third corner in (x,y,z) coords
        $y2 = $z0 - $k2 + 2.0 * self::G3;
        $z2 = $z0 - $k2 + 2.0 * self::G3;
        $x3 = $x0 - 1.0 + 3.0 * self::G3; // Offsets for last corner in (x,y,z) coords
        $y3 = $y0 - 1.0 + 3.0 * self::G3;
        $z3 = $z0 - 1.0 + 3.0 * self::G3;
        // Work out the hashed gradient indices of the four simplex corners
        $ii = $i & 255;
        $jj = $j & 255;
        $kk = $k & 255;
        $gi0 = $this->perm[$ii + $this->perm[$jj + $this->perm[$kk]]] % 12;
        $gi1 = $this->perm[$ii + $i1 + $this->perm[$jj + $$j1 + $this->perm[$kk + $k1]]] % 12;
        $gi2 = $this->perm[$ii + $i2 + $this->perm[$jj + $j2 + $this->perm[$kk + $k2]]] % 12;
        $gi3 = $this->perm[$ii + 1 + $this->perm[$jj + 1 + $this->perm[$kk + 1]]] % 12;
        // Calculate the contribution from the four corners
        $t0 = 0.6 - $x0 * $x0 - $y0 * $y0 - $z0 * $z0;
        if ($t0 < 0) {
            $n0 = 0.0;
        } else {
            $t0 *= $t0;
            $n0 = $t0 * $t0 * self::dot(self::GRAD3[$gi0], $x0, $y0, $z0);
        }
        $t1 = 0.6 - $x1 * $x1 - $y1 * $y1 - $z1 * $z1;
        if ($t1 < 0) {
            $n1 = 0.0;
        } else {
            $t1 *= $t1;
            $n1 = $t1 * $t1 * self::dot(self::GRAD3[$gi1], $x1, $y1, $z1);
        }
        $t2 = 0.6 - $x2 * $x2 - $y2 * $y2 - $z2 * $z2;
        if ($t2 < 0) {
            $n2 = 0.0;
        } else {
            $t2 *= $t2;
            $n2 = $t2 * $t2 * self::dot(self::GRAD3[$gi2], $x2, $y2, $z2);
        }
        $t3 = 0.6 - $x3 * $x3 - $y3 * $y3 - $z3 * $z3;
        if ($t3 < 0) {
            $n3 = 0.0;
        } else {
            $t3 *= $t3;
            $n3 = $t3 * $t3 * self::dot(self::GRAD3[$gi3], $x3, $y3, $z3);
        }
        // Add contributions from each corner to get the final noise value.
        // The result is scaled to stay just inside [-1,1]
        return 32.0 * ($n0 + $n1 + $n2 + $n3);
    }

    /**
     * @param int[]|float[] $g
     */
    protected static function dot(array $g, float $x, float $y, float $z = 0.0, float $w = 0.0) : float {
        $result = $g[0] * $x + $g[1] * $y;
        if ($z !== 0.0) {
            $result += $g[2] * $z;
            if ($w !== 0.0) {
                $result += $g[3] * $w;
            }
        }
        return $result;
    }

    public static function getInstance() : SimplexNoiseGenerator {
        return self::$instance ??= new SimplexNoiseGenerator();
    }

    /**
     * Computes and returns the 4D simplex noise for the given coordinates in
     * 4D space
     *
     * @param float $x X coordinate
     * @param float $y Y coordinate
     * @param float $z Z coordinate
     * @param float $w W coordinate
     * @return float noise at given location, from range -1 to 1
     */
    public static function getNoise(float $x, float $y, float $z, float $w) : float {
        return self::getInstance()->noise($x, $y, $z, $w);
    }

    /**
     * Computes and returns the 4D simplex noise for the given coordinates in
     * 4D space
     *
     * @param float $x X coordinate
     * @param float $y Y coordinate
     * @param float $z Z coordinate
     * @param float $w W coordinate
     * @return float noise at given location, from range -1 to 1
     */
    public function noise(float $x, float $y, float $z, float $w) : float {
        $x += $this->offsetX;
        $y += $this->offsetY;
        $z += $this->offsetZ;
        $w += self::$offsetW;
        // n0, n1, n2, n3, n5 - Noise contributions from the five corners
        // Skew the (x,y,z,w) space to determine which cell of 24 simplices we're in
        $s = ($x + $y + $z + $w) * self::F4; // Factor for 4D skewing
        $i = self::floor($x + $s);
        $j = self::floor($y + $s);
        $k = self::floor($z + $s);
        $l = self::floor($w + $s);
        $t = ($i + $j + $k + $l) * self::G4; // Factor for 4D unskewing
        $X0 = $i - $t;                       // Unskew the cell origin back to (x,y,z,w) space
        $Y0 = $j - $t;
        $Z0 = $k - $t;
        $W0 = $l - $t;
        $x0 = $x - $X0;             // The x,y,z,w distances from the cell origin
        $y0 = $y - $Y0;
        $z0 = $z - $Z0;
        $w0 = $w - $W0;
        // For the 4D case, the simplex is a 4D shape I won't even try to describe.
        // To find out which of the 24 possible simplices we're in, we need to
        // determine the magnitude ordering of x0, y0, z0 and w0.
        // The method below is a good way of finding the ordering of x,y,z,w and
        // then find the correct traversal order for the simplex we’re in.
        // First, six pair-wise comparisons are performed between each possible pair
        // of the four coordinates, and the results are used to add up binary bits
        // for an integer index.
        $c1 = ($x0 > $y0) ? 32 : 0;
        $c2 = ($x0 > $z0) ? 16 : 0;
        $c3 = ($y0 > $z0) ? 8 : 0;
        $c4 = ($x0 > $w0) ? 4 : 0;
        $c5 = ($y0 > $w0) ? 2 : 0;
        $c6 = ($z0 > $w0) ? 1 : 0;
        $c = $c1 + $c2 + $c3 + $c4 + $c5 + $c6;
        // i1, j1, k1, l1 - The integer offsets for the second simplex corner
        // i2, j2, k2, l2 - The integer offsets for the third simplex corner
        // i3, j3, k3, l3 - The integer offsets for the fourth simplex corner
        // self::SIMPLEX[c] is a 4-vector with the numbers 0, 1, 2 and 3 in some order.
        // Many values of c will never occur, since e.g. x>y>z>w makes x<z, y<w and x<w
        // impossible. Only the 24 indices which have non-zero entries make any sense.
        // We use a thresholding to set the coordinates in turn from the largest magnitude.
        // The number 3 in the "simplex" array is at the position of the largest coordinate.
        $i1 = self::SIMPLEX[$c][0] >= 3 ? 1 : 0;
        $j1 = self::SIMPLEX[$c][1] >= 3 ? 1 : 0;
        $k1 = self::SIMPLEX[$c][2] >= 3 ? 1 : 0;
        $l1 = self::SIMPLEX[$c][3] >= 3 ? 1 : 0;
        // The number 2 in the "simplex" array is at the second largest coordinate.
        $i2 = self::SIMPLEX[$c][0] >= 2 ? 1 : 0;
        $j2 = self::SIMPLEX[$c][1] >= 2 ? 1 : 0;
        $k2 = self::SIMPLEX[$c][2] >= 2 ? 1 : 0;
        $l2 = self::SIMPLEX[$c][3] >= 2 ? 1 : 0;
        // The number 1 in the "simplex" array is at the second smallest coordinate.
        $i3 = self::SIMPLEX[$c][0] >= 1 ? 1 : 0;
        $j3 = self::SIMPLEX[$c][1] >= 1 ? 1 : 0;
        $k3 = self::SIMPLEX[$c][2] >= 1 ? 1 : 0;
        $l3 = self::SIMPLEX[$c][3] >= 1 ? 1 : 0;
        // The fifth corner has all coordinate offsets = 1, so no need to look that up.
        $x1 = $x0 - $i1 + self::G4; // Offsets for second corner in (x,y,z,w) coords
        $y1 = $y0 - $j1 + self::G4;
        $z1 = $z0 - $k1 + self::G4;
        $w1 = $w0 - $l1 + self::G4;
        $x2 = $x0 - $i2 + self::G42; // Offsets for third corner in (x,y,z,w) coords
        $y2 = $y0 - $j2 + self::G42;
        $z2 = $z0 - $k2 + self::G42;
        $w2 = $w0 - $l2 + self::G42;
        $x3 = $x0 - $i3 + self::G43; // Offsets for fourth corner in (x,y,z,w) coords
        $y3 = $y0 - $j3 + self::G43;
        $z3 = $z0 - $k3 + self::G43;
        $w3 = $w0 - $l3 + self::G43;
        $x4 = $x0 + self::G44; // Offsets for last corner in (x,y,z,w) coords
        $y4 = $y0 + self::G44;
        $z4 = $z0 + self::G44;
        $w4 = $w0 + self::G44;
        // Work out the hashed gradient indices of the five simplex corners
        $ii = $i & 255;
        $jj = $j & 255;
        $kk = $k & 255;
        $ll = $l & 255;
        $gi0 = $this->perm[$ii + $this->perm[$jj + $this->perm[$kk + $this->perm[$ll]]]] % 32;
        $gi1 = $this->perm[$ii + $i1 + $this->perm[$jj + $j1 + $this->perm[$kk + $k1 + $this->perm[$ll + $l1]]]] % 32;
        $gi2 = $this->perm[$ii + $i2 + $this->perm[$jj + $j2 + $this->perm[$kk + $k2 + $this->perm[$ll + $l2]]]] % 32;
        $gi3 = $this->perm[$ii + $i3 + $this->perm[$jj + $j3 + $this->perm[$kk + $k3 + $this->perm[$ll + $l3]]]] % 32;
        $gi4 = $this->perm[$ii + 1 + $this->perm[$jj + 1 + $this->perm[$kk + 1 + $this->perm[$ll + 1]]]] % 32;
        // Calculate the contribution from the five corners
        $t0 = 0.6 - $x0 * $x0 - $y0 * $y0 - $z0 * $z0 - $w0 * $w0;
        if ($t0 < 0) {
            $n0 = 0.0;
        } else {
            $t0 *= $t0;
            $n0 = $t0 * $t0 * self::dot(self::GRAD4[$gi0], $x0, $y0, $z0, $w0);
        }
        $t1 = 0.6 - $x1 * $x1 - $y1 * $y1 - $z1 * $z1 - $w1 * $w1;
        if ($t1 < 0) {
            $n1 = 0.0;
        } else {
            $t1 *= $t1;
            $n1 = $t1 * $t1 * self::dot(self::GRAD4[$gi1], $x1, $y1, $z1, $w1);
        }
        $t2 = 0.6 - $x2 * $x2 - $y2 * $y2 - $z2 * $z2 - $w2 * $w2;
        if ($t2 < 0) {
            $n2 = 0.0;
        } else {
            $t2 *= $t2;
            $n2 = $t2 * $t2 * self::dot(self::GRAD4[$gi2], $x2, $y2, $z2, $w2);
        }
        $t3 = 0.6 - $x3 * $x3 - $y3 * $y3 - $z3 * $z3 - $w3 * $w3;
        if ($t3 < 0) {
            $n3 = 0.0;
        } else {
            $t3 *= $t3;
            $n3 = $t3 * $t3 * self::dot(self::GRAD4[$gi3], $x3, $y3, $z3, $w3);
        }
        $t4 = 0.6 - $x4 * $x4 - $y4 * $y4 - $z4 * $z4 - $w4 * $w4;
        if ($t4 < 0) {
            $n4 = 0.0;
        } else {
            $t4 *= $t4;
            $n4 = $t4 * $t4 * self::dot(self::GRAD4[$gi4], $x4, $y4, $z4, $w4);
        }
        // Sum up and scale the result to cover the range [-1,1]
        return 27.0 * ($n0 + $n1 + $n2 + $n3 + $n4);
    }
}
