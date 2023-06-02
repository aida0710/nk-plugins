<?php

declare(strict_types = 0);

namespace czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\overworld\populator\biome;

use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\object\tree\AcaciaTree;
use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\object\tree\GenericTree;
use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\overworld\biome\BiomeIds;
use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\overworld\decorator\types\DoublePlantDecoration;
use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\overworld\decorator\types\TreeDecoration;
use pocketmine\block\VanillaBlocks;

class SavannaPopulator extends BiomePopulator {

    /** @var DoublePlantDecoration[] */
    protected static array $DOUBLE_PLANTS;

    /** @var TreeDecoration[] */
    protected static array $TREES;

    public function getBiomes() : ?array {
        return [BiomeIds::SAVANNA, BiomeIds::SAVANNA_PLATEAU];
    }

    public static function init() : void {
        parent::init();
        self::$DOUBLE_PLANTS = [
            new DoublePlantDecoration(VanillaBlocks::DOUBLE_TALLGRASS(), 1),
        ];
    }

    protected function initPopulators() : void {
        $this->doublePlantDecorator->setAmount(7);
        $this->doublePlantDecorator->setDoublePlants(...self::$DOUBLE_PLANTS);
        $this->treeDecorator->setAmount(1);
        $this->treeDecorator->setTrees(...self::$TREES);
        $this->flowerDecorator->setAmount(4);
        $this->tallGrassDecorator->setAmount(20);
    }

    protected static function initTrees() : void {
        self::$TREES = [
            new TreeDecoration(AcaciaTree::class, 4),
            new TreeDecoration(GenericTree::class, 4),
        ];
    }
}

SavannaPopulator::init();
