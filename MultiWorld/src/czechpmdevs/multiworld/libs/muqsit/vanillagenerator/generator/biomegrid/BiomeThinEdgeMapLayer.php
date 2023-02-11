<?php

declare(strict_types = 0);
namespace czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\biomegrid;

use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\biomegrid\utils\BiomeEdgeEntry;
use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\overworld\biome\BiomeIds;
use function array_flip;
use function array_key_exists;

class BiomeThinEdgeMapLayer extends MapLayer {

	/** @var int[] */
	private static array $OCEANS = [BiomeIds::OCEAN, BiomeIds::DEEP_OCEAN];

	/** @var int[] */
	private static array $MESA_EDGES = [
		BiomeIds::MESA => BiomeIds::DESERT,
		BiomeIds::MESA_BRYCE => BiomeIds::DESERT,
		BiomeIds::MESA_PLATEAU_STONE => BiomeIds::DESERT,
		BiomeIds::MESA_PLATEAU_STONE_MUTATED => BiomeIds::DESERT,
		BiomeIds::MESA_PLATEAU => BiomeIds::DESERT,
		BiomeIds::MESA_PLATEAU_MUTATED => BiomeIds::DESERT,
	];

	/** @var int[] */
	private static array $JUNGLE_EDGES = [
		BiomeIds::JUNGLE => BiomeIds::JUNGLE_EDGE,
		BiomeIds::JUNGLE_HILLS => BiomeIds::JUNGLE_EDGE,
		BiomeIds::JUNGLE_MUTATED => BiomeIds::JUNGLE_EDGE,
		BiomeIds::JUNGLE_EDGE_MUTATED => BiomeIds::JUNGLE_EDGE,
	];

	/** @var BiomeEdgeEntry[] */
	private static array $EDGES;

	public static function init() : void {
		self::$OCEANS = array_flip(self::$OCEANS);
		self::$EDGES = [
			new BiomeEdgeEntry(self::$MESA_EDGES),
			new BiomeEdgeEntry(self::$JUNGLE_EDGES, [BiomeIds::JUNGLE, BiomeIds::JUNGLE_HILLS, BiomeIds::JUNGLE_MUTATED, BiomeIds::JUNGLE_EDGE_MUTATED, BiomeIds::FOREST, BiomeIds::TAIGA]),
		];
	}

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
				// This applies biome thin edges using Von Neumann neighborhood
				$centerVal = $values[$j + 1 + ($i + 1) * $gridSizeX];
				$val = $centerVal;
				foreach (self::$EDGES as $edge) {
					if (array_key_exists($centerVal, $edge->key)) {
						$upperVal = $values[$j + 1 + $i * $gridSizeX];
						$lowerVal = $values[$j + 1 + ($i + 2) * $gridSizeX];
						$leftVal = $values[$j + ($i + 1) * $gridSizeX];
						$rightVal = $values[$j + 2 + ($i + 1) * $gridSizeX];
						if ($edge->value === null && (
								(!array_key_exists($upperVal, self::$OCEANS) && !array_key_exists($upperVal, $edge->key))
								|| (!array_key_exists($lowerVal, self::$OCEANS) && !array_key_exists($lowerVal, $edge->key))
								|| (!array_key_exists($leftVal, self::$OCEANS) && !array_key_exists($leftVal, $edge->key))
								|| (!array_key_exists($rightVal, self::$OCEANS) && !array_key_exists($rightVal, $edge->key))
							)) {
							$val = $edge->key[$centerVal];
							break;
						}
						if ($edge->value !== null && (
								(!array_key_exists($upperVal, self::$OCEANS) && !array_key_exists($upperVal, $edge->value))
								|| (!array_key_exists($lowerVal, self::$OCEANS) && !array_key_exists($lowerVal, $edge->value))
								|| (!array_key_exists($leftVal, self::$OCEANS) && !array_key_exists($leftVal, $edge->value))
								|| (!array_key_exists($rightVal, self::$OCEANS) && !array_key_exists($rightVal, $edge->value))
							)) {
							$val = $edge->key[$centerVal];
							break;
						}
					}
				}
				$finalValues[$j + $i * $sizeX] = $val;
			}
		}
		return $finalValues;
	}
}

BiomeThinEdgeMapLayer::init();
