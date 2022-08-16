<?php

declare(strict_types = 1);
namespace czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\object;

use pocketmine\block\Flowable;
use pocketmine\block\VanillaBlocks;
use pocketmine\utils\Random;
use pocketmine\world\ChunkManager;
use pocketmine\world\World;

abstract class TerrainObject {

    /**
     * Removes weak blocks like grass, shrub, flower or mushroom directly above the given block, if present.
     * Does not drop an item.
     *
     * @param ChunkManager $world
     * @param int $x
     * @param int $y
     * @param int $z
     * @return bool whether a block was removed; false if none was present
     */
    public static function killWeakBlocksAbove(ChunkManager $world, int $x, int $y, int $z): bool {
        $changed = false;
        for ($cur_y = $y + 1; $cur_y < World::Y_MAX; ++$cur_y) {
            $block = $world->getBlockAt($x, $cur_y, $z);
            if (!($block instanceof Flowable)) {
                break;
            }
            $world->setBlockAt($x, $cur_y, $z, VanillaBlocks::AIR());
            $changed = true;
            ++$cur_y;
        }
        return $changed;
    }

    /**
     * Generates this feature.
     *
     * @param ChunkManager $world the world to generate in
     * @param Random $random the PRNG that will choose the size and a few details of the shape
     * @param int $source_x the base X coordinate
     * @param int $source_y the base Y coordinate
     * @param int $source_z the base Z coordinate
     * @return bool if successfully generated
     */
    abstract public function generate(ChunkManager $world, Random $random, int $source_x, int $source_y, int $source_z): bool;
}