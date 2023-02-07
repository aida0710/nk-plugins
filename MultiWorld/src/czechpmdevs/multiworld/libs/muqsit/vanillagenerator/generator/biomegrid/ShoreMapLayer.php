<?php

declare(strict_types = 1);
namespace czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\biomegrid;

use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\overworld\biome\BiomeIds;
use function array_key_exists;

class ShoreMapLayer extends MapLayer {

	/** @var int[] */
	private static array $OCEANS = [BiomeIds::OCEAN => 0, BiomeIds::DEEP_OCEAN => 0];

	/** @var int[] */
	private static array $SPECIAL_SHORES = [
		BiomeIds::EXTREME_HILLS => BiomeIds::STONE_BEACH,
		BiomeIds::EXTREME_HILLS_PLUS_TREES => BiomeIds::STONE_BEACH,
		BiomeIds::EXTREME_HILLS_MUTATED => BiomeIds::STONE_BEACH,
		BiomeIds::EXTREME_HILLS_PLUS_TREES_MUTATED => BiomeIds::STONE_BEACH,
		BiomeIds::ICE_PLAINS => BiomeIds::COLD_BEACH,
		BiomeIds::ICE_MOUNTAINS => BiomeIds::COLD_BEACH,
		BiomeIds::ICE_PLAINS_SPIKES => BiomeIds::COLD_BEACH,
		BiomeIds::COLD_TAIGA => BiomeIds::COLD_BEACH,
		BiomeIds::COLD_TAIGA_HILLS => BiomeIds::COLD_BEACH,
		BiomeIds::COLD_TAIGA_MUTATED => BiomeIds::COLD_BEACH,
		BiomeIds::MUSHROOM_ISLAND => BiomeIds::MUSHROOM_ISLAND_SHORE,
		BiomeIds::SWAMPLAND => BiomeIds::SWAMPLAND,
		BiomeIds::MESA => BiomeIds::MESA,
		BiomeIds::MESA_PLATEAU_STONE => BiomeIds::MESA_PLATEAU_STONE,
		BiomeIds::MESA_PLATEAU_STONE_MUTATED => BiomeIds::MESA_PLATEAU_STONE_MUTATED,
		BiomeIds::MESA_PLATEAU => BiomeIds::MESA_PLATEAU,
		BiomeIds::MESA_PLATEAU_MUTATED => BiomeIds::MESA_PLATEAU_MUTATED,
		BiomeIds::MESA_BRYCE => BiomeIds::MESA_BRYCE,
	];

	private MapLayer $belowLayer;

	public function __construct(int $seed, MapLayer $belowLayer) {
		parent::__construct($seed);
		$this->belowLayer = $belowLayer;
	}

	public function generateValues(int $x, int $z, int $sizeX, int $sizeZ) : array {
		$gridX = $x - 1;
		$gridZ = $z - 1;
		$gridSizeX = $sizeX + 2;
		$gridSizeZ = $sizeZ + 2;
		$values = $this->belowLayer->generateValues($gridX, $gridZ, $gridSizeX, $gridSizeZ);
		$finalValues = [];
		for ($i = 0; $i < $sizeZ; ++$i) {
			for ($j = 0; $j < $sizeX; ++$j) {
				// This applies shores using Von Neumann neighborhood
				// it takes a 3x3 grid with a cross shape and analyzes values as follow
				// 0X0
				// XxX
				// 0X0
				// the grid center value decides how we are proceeding:
				// - if it's not ocean and it's surrounded by at least 1 ocean cell
				// it turns the center value into beach.
				$upperVal = $values[$j + 1 + $i * $gridSizeX];
				$lowerVal = $values[$j + 1 + ($i + 2) * $gridSizeX];
				$leftVal = $values[$j + ($i + 1) * $gridSizeX];
				$rightVal = $values[$j + 2 + ($i + 1) * $gridSizeX];
				$centerVal = $values[$j + 1 + ($i + 1) * $gridSizeX];
				if (!array_key_exists($centerVal, self::$OCEANS) && (
						array_key_exists($upperVal, self::$OCEANS) || array_key_exists($lowerVal, self::$OCEANS)
						|| array_key_exists($leftVal, self::$OCEANS) || array_key_exists($rightVal, self::$OCEANS)
					)) {
					$finalValues[$j + $i * $sizeX] = self::$SPECIAL_SHORES[$centerVal] ?? BiomeIds::BEACH;
				} else {
					$finalValues[$j + $i * $sizeX] = $centerVal;
				}
			}
		}
		return $finalValues;
	}
}
