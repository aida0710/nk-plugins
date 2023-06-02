<?php

declare(strict_types = 0);

namespace czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\overworld\populator\biome;

use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\object\tree\MegaPineTree;
use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\object\tree\MegaSpruceTree;
use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\object\tree\RedwoodTree;
use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\object\tree\TallRedwoodTree;
use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\overworld\biome\BiomeIds;
use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\overworld\decorator\StoneBoulderDecorator;
use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\overworld\decorator\types\TreeDecoration;
use pocketmine\utils\Random;
use pocketmine\world\ChunkManager;
use pocketmine\world\format\Chunk;

class MegaTaigaPopulator extends TaigaPopulator {

    /** @var TreeDecoration[] */
    protected static array $TREES;
    protected StoneBoulderDecorator $stoneBoulderDecorator;

    public function __construct() {
        parent::__construct();
        $this->stoneBoulderDecorator = new StoneBoulderDecorator();
    }

    protected static function initTrees() : void {
        self::$TREES = [
            new TreeDecoration(RedwoodTree::class, 52),
            new TreeDecoration(TallRedwoodTree::class, 26),
            new TreeDecoration(MegaPineTree::class, 36),
            new TreeDecoration(MegaSpruceTree::class, 3),
        ];
    }

    public function getBiomes() : ?array {
        return [BiomeIds::MEGA_TAIGA, BiomeIds::MEGA_TAIGA_HILLS];
    }

    protected function initPopulators() : void {
        $this->treeDecorator->setTrees(...self::$TREES);
        $this->tallGrassDecorator->setAmount(7);
        $this->deadBushDecorator->setAmount(0);
        $this->taigaBrownMushroomDecorator->setAmount(3);
        $this->taigaRedMushroomDecorator->setAmount(3);
    }

    protected function populateOnGround(ChunkManager $world, Random $random, int $chunkX, int $chunkZ, Chunk $chunk) : void {
        $this->stoneBoulderDecorator->populate($world, $random, $chunkX, $chunkZ, $chunk);
        parent::populateOnGround($world, $random, $chunkX, $chunkZ, $chunk);
    }
}

MegaTaigaPopulator::init();
