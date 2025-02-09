<?php

declare(strict_types = 0);

namespace czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\overworld\decorator;

use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\Decorator;
use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\object\tree\GenericTree;
use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\overworld\decorator\types\TreeDecoration;
use Exception;
use pocketmine\utils\Random;
use pocketmine\world\BlockTransaction;
use pocketmine\world\ChunkManager;
use pocketmine\world\format\Chunk;

class TreeDecorator extends Decorator {

    /** @var TreeDecoration[] */
    private array $trees = [];

    final public function setTrees(TreeDecoration ...$trees) : void {
        $this->trees = $trees;
    }

    public function populate(ChunkManager $world, Random $random, int $chunkX, int $chunkZ, Chunk $chunk) : void {
        $treeAmount = $this->amount;
        if ($random->nextBoundedInt(10) === 0) {
            ++$treeAmount;
        }
        for ($i = 0; $i < $treeAmount; ++$i) {
            $this->decorate($world, $random, $chunkX, $chunkZ, $chunk);
        }
    }

    public function decorate(ChunkManager $world, Random $random, int $chunkX, int $chunkZ, Chunk $chunk) : void {
        $x = $random->nextBoundedInt(16);
        $z = $random->nextBoundedInt(16);
        $sourceY = $chunk->getHighestBlockAt($x, $z);
        $class = self::getRandomTree($random, $this->trees);
        if ($class !== null) {
            $txn = new BlockTransaction($world);
            try {
                /** @var GenericTree $tree */
                $tree = new $class($random, $txn);
            } catch (Exception) {
                $tree = new GenericTree($random, $txn);
            }
            if ($tree->generate($world, $random, ($chunkX << 4) + $x, $sourceY, ($chunkZ << 4) + $z)) {
                $txn->apply();
            }
        }
    }

    /**
     * @param TreeDecoration[] $decorations
     * @return ?string $genericTreeClass a GenericTree class
     */
    private static function getRandomTree(Random $random, array $decorations) : ?string {
        $totalWeight = 0;
        foreach ($decorations as $decoration) {
            $totalWeight += $decoration->getWeight();
        }
        if ($totalWeight > 0) {
            $weight = $random->nextBoundedInt($totalWeight);
            foreach ($decorations as $decoration) {
                $weight -= $decoration->getWeight();
                if ($weight < 0) {
                    return $decoration->getClass();
                }
            }
        }
        return null;
    }
}
