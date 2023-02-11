<?php

declare(strict_types = 0);
namespace czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\biomegrid\utils;

use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\biomegrid\MapLayer;

final class MapLayerPair {

	public MapLayer $highResolution;
	public ?MapLayer $lowResolution;

	public function __construct(MapLayer $highResolution, ?MapLayer $lowResolution) {
		$this->highResolution = $highResolution;
		$this->lowResolution = $lowResolution;
	}
}
