<?php

declare(strict_types = 0);

namespace czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\object\tree;

use czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\object\TerrainObject;
use pocketmine\block\Block;
use pocketmine\block\BlockFactory;
use pocketmine\block\BlockLegacyIds;
use pocketmine\block\utils\TreeType;
use pocketmine\block\VanillaBlocks;
use pocketmine\utils\Random;
use pocketmine\world\BlockTransaction;
use pocketmine\world\ChunkManager;
use pocketmine\world\World;
use function abs;
use function array_flip;
use function array_key_exists;

class GenericTree extends TerrainObject {

    protected BlockTransaction $transaction;
    protected int $height;
    protected Block $logType;
    protected Block $leavesType;

    /** @var int[] */
    protected array $overridables;

    /**
     * Initializes this tree with a random height, preparing it to attempt to generate.
     *
     * @param Random           $random      the PRNG
     * @param BlockTransaction $transaction the BlockTransaction used to check for space and to fill in wood and leaves
     */
    public function __construct(Random $random, BlockTransaction $transaction) {
        $this->transaction = $transaction;
        $this->setOverridables(
            BlockLegacyIds::AIR,
            BlockLegacyIds::LEAVES,
            BlockLegacyIds::GRASS,
            BlockLegacyIds::DIRT,
            BlockLegacyIds::LOG,
            BlockLegacyIds::LOG2,
            BlockLegacyIds::SAPLING,
            BlockLegacyIds::VINE,
        );
        $this->setHeight($random->nextBoundedInt(3) + 4);
        $this->setType(TreeType::OAK());
    }

    final protected function setOverridables(int ...$overridables) : void {
        $this->overridables = array_flip($overridables);
    }

    final protected function setHeight(int $height) : void {
        $this->height = $height;
    }

    /**
     * Sets the block data values for this tree's blocks.
     */
    final protected function setType(TreeType $type) : void {
        $magicNumber = $type->getMagicNumber();
        $blockFactory = BlockFactory::getInstance();
        $this->logType = $blockFactory->get($magicNumber >= 4 ? BlockLegacyIds::LOG2 : BlockLegacyIds::LOG, $magicNumber & 0x3);
        $this->leavesType = $blockFactory->get($magicNumber >= 4 ? BlockLegacyIds::LEAVES2 : BlockLegacyIds::LEAVES, $magicNumber & 0x3);
    }

    /**
     * Attempts to grow this tree at its current location. If successful, the associated {@link
     * BlockStateDelegate} is instructed to set blocks to wood and leaves.
     *
     * @return bool whether successfully grown
     */
    public function generate(ChunkManager $world, Random $random, int $sourceX, int $sourceY, int $sourceZ) : bool {
        if ($this->cannotGenerateAt($sourceX, $sourceY, $sourceZ, $world)) {
            return false;
        }
        // generate the leaves
        for ($y = $sourceY + $this->height - 3; $y <= $sourceY + $this->height; ++$y) {
            $n = $y - ($sourceY + $this->height);
            $radius = (int) (1 - $n / 2);
            for ($x = $sourceX - $radius; $x <= $sourceX + $radius; ++$x) {
                for ($z = $sourceZ - $radius; $z <= $sourceZ + $radius; ++$z) {
                    if (abs($x - $sourceX) !== $radius
                        || abs($z - $sourceZ) !== $radius
                        || ($random->nextBoolean() && $n !== 0)
                    ) {
                        $this->replaceIfAirOrLeaves($x, $y, $z, $this->leavesType, $world);
                    }
                }
            }
        }
        // generate the trunk
        for ($y = 0; $y < $this->height; ++$y) {
            $this->replaceIfAirOrLeaves($sourceX, $sourceY + $y, $sourceZ, $this->logType, $world);
        }
        // block below trunk is always dirt
        $dirt = VanillaBlocks::DIRT();
        $this->transaction->addBlockAt($sourceX, $sourceY - 1, $sourceZ, $dirt);
        return true;
    }

    /**
     * Returns whether any of {@link #canHeightFit(int)}, {@link #canPlace(int, int, int, World)} or
     * {@link #canPlaceOn(BlockState)} prevent this tree from generating.
     *
     * @param int          $baseX the X coordinate of the base of the trunk
     * @param int          $baseY the Y coordinate of the base of the trunk
     * @param int          $baseZ the Z coordinate of the base of the trunk
     * @param ChunkManager $world the world to grow in
     * @return bool whether any of the checks prevent us from generating, false otherwise
     */
    protected function cannotGenerateAt(int $baseX, int $baseY, int $baseZ, ChunkManager $world) : bool {
        return !$this->canHeightFit($baseY)
            || !$this->canPlaceOn($world->getBlockAt($baseX, $baseY - 1, $baseZ))
            || !$this->canPlace($baseX, $baseY, $baseZ, $world);
    }

    /**
     * Checks whether this tree fits under the upper world limit.
     *
     * @param int $baseHeight the height of the base of the trunk
     *
     * @return bool whether this tree can grow without exceeding block height 255; false otherwise.
     */
    public function canHeightFit(int $baseHeight) : bool {
        return $baseHeight >= 1 && $baseHeight + $this->height + 1 < World::Y_MAX;
    }

    /**
     * Checks whether this tree can grow on top of the given block.
     *
     * @param Block $soil the block we're growing on
     * @return bool whether this tree can grow on the type of block below it; false otherwise
     */
    public function canPlaceOn(Block $soil) : bool {
        $type = $soil->getId();
        return $type === BlockLegacyIds::GRASS || $type === BlockLegacyIds::DIRT || $type === BlockLegacyIds::FARMLAND;
    }

    /**
     * Checks whether this tree has enough space to grow.
     *
     * @param int          $baseX the X coordinate of the base of the trunk
     * @param int          $baseY the Y coordinate of the base of the trunk
     * @param int          $baseZ the Z coordinate of the base of the trunk
     * @param ChunkManager $world the world to grow in
     * @return bool whether this tree has space to grow
     */
    public function canPlace(int $baseX, int $baseY, int $baseZ, ChunkManager $world) : bool {
        for ($y = $baseY; $y <= $baseY + 1 + $this->height; ++$y) {
            // Space requirement
            $radius = 1; // default radius if above first block
            if ($y === $baseY) {
                $radius = 0; // radius at source block y is 0 (only trunk)
            } elseif ($y >= $baseY + 1 + $this->height - 2) {
                $radius = 2; // max radius starting at leaves bottom
            }
            // check for block collision on horizontal slices
            $height = $world->getMaxY();
            for ($x = $baseX - $radius; $x <= $baseX + $radius; ++$x) {
                for ($z = $baseZ - $radius; $z <= $baseZ + $radius; ++$z) {
                    if ($y >= 0 && $y < $height) {
                        // we can overlap some blocks around
                        if (!array_key_exists($world->getBlockAt($x, $y, $z)->getId(), $this->overridables)) {
                            return false;
                        }
                    } else { // height out of range
                        return false;
                    }
                }
            }
        }
        return true;
    }

    /**
     * Replaces the block at a location with the given new one, if it is air or leaves.
     *
     * @param int          $x           the x coordinate
     * @param int          $y           the y coordinate
     * @param int          $z           the z coordinate
     * @param Block        $newMaterial the new block type
     * @param ChunkManager $world       the world we are generating in
     */
    protected function replaceIfAirOrLeaves(int $x, int $y, int $z, Block $newMaterial, ChunkManager $world) : void {
        $oldMaterial = $world->getBlockAt($x, $y, $z)->getId();
        if ($oldMaterial === BlockLegacyIds::AIR || $oldMaterial === BlockLegacyIds::LEAVES) {
            $this->transaction->addBlockAt($x, $y, $z, $newMaterial);
        }
    }
}
