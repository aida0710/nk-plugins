<?php

declare(strict_types = 0);

namespace czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\object;

use pocketmine\block\BlockLegacyIds;
use pocketmine\block\DoublePlant;
use pocketmine\utils\Random;
use pocketmine\world\ChunkManager;

class DoubleTallPlant extends TerrainObject {

    private DoublePlant $species;

    public function __construct(DoublePlant $species) {
        $this->species = $species;
    }

    /**
     * Generates up to 64 plants around the given point.
     *
     * @return bool true whether least one plant was successfully generated
     */
    public function generate(ChunkManager $world, Random $random, int $sourceX, int $sourceY, int $sourceZ) : bool {
        $placed = false;
        $height = $world->getMaxY();
        for ($i = 0; $i < 64; ++$i) {
            $x = $sourceX + $random->nextBoundedInt(8) - $random->nextBoundedInt(8);
            $z = $sourceZ + $random->nextBoundedInt(8) - $random->nextBoundedInt(8);
            $y = $sourceY + $random->nextBoundedInt(4) - $random->nextBoundedInt(4);
            $block = $world->getBlockAt($x, $y, $z);
            $topBlock = $world->getBlockAt($x, $y + 1, $z);
            if ($y < $height && $block->getId() === BlockLegacyIds::AIR && $topBlock->getId() === BlockLegacyIds::AIR && $world->getBlockAt($x, $y - 1, $z)->getId() === BlockLegacyIds::GRASS) {
                $world->setBlockAt($x, $y, $z, $this->species->setTop(false));
                $world->setBlockAt($x, $y + 1, $z, $this->species->setTop(true));
                $placed = true;
            }
        }
        return $placed;
    }
}
