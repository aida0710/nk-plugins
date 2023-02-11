<?php

declare(strict_types = 0);
namespace czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\ground;

use pocketmine\block\VanillaBlocks;
use pocketmine\utils\Random;
use pocketmine\world\ChunkManager;

class DirtPatchGroundGenerator extends GroundGenerator {

	public function generateTerrainColumn(ChunkManager $world, Random $random, int $x, int $z, int $biome, float $surfaceNoise) : void {
		if ($surfaceNoise > 1.75) {
			$this->setTopMaterial(VanillaBlocks::DIRT()->setCoarse(true));
		} elseif ($surfaceNoise > -0.95) {
			$this->setTopMaterial(VanillaBlocks::PODZOL());
		} else {
			$this->setTopMaterial(VanillaBlocks::GRASS());
		}
		$this->setGroundMaterial(VanillaBlocks::DIRT());
		parent::generateTerrainColumn($world, $random, $x, $z, $biome, $surfaceNoise);
	}
}
