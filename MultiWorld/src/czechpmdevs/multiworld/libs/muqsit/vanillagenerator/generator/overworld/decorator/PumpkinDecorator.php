<?php

declare(strict_types = 0);

namespace czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\overworld\decorator;

use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\Decorator;
use pocketmine\block\BlockFactory;
use pocketmine\block\BlockLegacyIds;
use pocketmine\math\Facing;
use pocketmine\utils\Random;
use pocketmine\world\ChunkManager;
use pocketmine\world\format\Chunk;
use function count;

class PumpkinDecorator extends Decorator {

    private const FACES = [Facing::NORTH, Facing::EAST, Facing::SOUTH, Facing::WEST];

    public function decorate(ChunkManager $world, Random $random, int $chunkX, int $chunkZ, Chunk $chunk) : void {
        if ($random->nextBoundedInt(32) === 0) {
            $sourceX = ($chunkX << 4) + $random->nextBoundedInt(16);
            $sourceZ = ($chunkZ << 4) + $random->nextBoundedInt(16);
            $sourceY = $random->nextBoundedInt($chunk->getHighestBlockAt($sourceX & 0x0f, $sourceZ & 0x0f) << 1);
            $blockFactory = BlockFactory::getInstance();
            for ($i = 0; $i < 64; ++$i) {
                $x = $sourceX + $random->nextBoundedInt(8) - $random->nextBoundedInt(8);
                $z = $sourceZ + $random->nextBoundedInt(8) - $random->nextBoundedInt(8);
                $y = $sourceY + $random->nextBoundedInt(4) - $random->nextBoundedInt(4);
                if ($world->getBlockAt($x, $y, $z)->getId() === BlockLegacyIds::AIR && $world->getBlockAt($x, $y - 1, $z)->getId() === BlockLegacyIds::GRASS) {
                    $world->setBlockAt($x, $y, $z, $blockFactory->get(BlockLegacyIds::PUMPKIN, self::FACES[$random->nextBoundedInt(count(self::FACES))]));
                }
            }
        }
    }
}
