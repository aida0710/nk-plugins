<?php

namespace tedo0627\redstonecircuit\block\transmission;

use pocketmine\block\Block;
use pocketmine\block\RedstoneWire;
use pocketmine\block\Slab;
use pocketmine\block\Stair;
use pocketmine\block\utils\SlabType;
use pocketmine\item\Item;
use pocketmine\math\Facing;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\world\BlockTransaction;
use tedo0627\redstonecircuit\block\BlockPowerHelper;
use tedo0627\redstonecircuit\block\BlockUpdateHelper;
use tedo0627\redstonecircuit\block\FlowablePlaceHelper;
use tedo0627\redstonecircuit\block\ILinkRedstoneWire;
use tedo0627\redstonecircuit\block\IRedstoneComponent;
use tedo0627\redstonecircuit\block\RedstoneComponentTrait;

class BlockRedstoneWire extends RedstoneWire implements IRedstoneComponent, ILinkRedstoneWire {
    use RedstoneComponentTrait;

    public function place(BlockTransaction $tx, Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, ?Player $player = null): bool {
        if (!FlowablePlaceHelper::check($this, Facing::DOWN)) return false;
        return parent::place($tx, $item, $blockReplace, $blockClicked, $face, $clickVector, $player);
    }

    public function onPostPlace(): void {
        $this->calculatePower();
    }

    public function onBreak(Item $item, ?Player $player = null): bool {
        $bool = parent::onBreak($item, $player);
        BlockUpdateHelper::updateAroundStrongRedstone($this);
        return $bool;
    }

    public function onNearbyBlockChange(): void {
        if (FlowablePlaceHelper::check($this, Facing::DOWN)) {
            if ($this->calculatePower()) return;
            BlockUpdateHelper::updateAroundStrongRedstone($this);
        } else {
            $this->getPosition()->getWorld()->useBreakOn($this->getPosition());
        }
    }

    public function getStrongPower(int $face): int {
        return $this->getWeakPower($face);
    }

    public function getWeakPower(int $face): int {
        if ($face == Facing::UP) return $this->getOutputSignalStrength();
        if ($face == Facing::DOWN) return 0;
        if ($this->isConnected(Facing::opposite($face))) return $this->getOutputSignalStrength();

        $right = Facing::rotateY($face, true);
        $left = Facing::rotateY($face, false);

        return $this->isConnected($right) || $this->isConnected($left) ? 0 : $this->getOutputSignalStrength();
    }

    private function isConnected(int $face): bool {
        $block = $this->getSide($face);
        if ($block instanceof ILinkRedstoneWire && $block->isConnect($face)) return true;

        if (BlockPowerHelper::isNormalBlock($block)) {
            $sideBlock = $block->getSide(Facing::UP);
            return $sideBlock instanceof RedstoneWire;
        }

        if ($block->isTransparent()) {
            $sideBlock = $block->getSide(Facing::DOWN);
            return $sideBlock instanceof RedstoneWire;
        }
        return false;
    }

    public function onRedstoneUpdate(): void {
        $this->calculatePower();
    }

    private function calculatePower(): bool {
        $power = 0;
        for ($face = 0; $face < 6; $face++) {
            $block = $this->getSide($face);
            if ($block instanceof BlockRedstoneWire) {
                $power = max($power, $block->getOutputSignalStrength() - 1);
                continue;
            }

            if (BlockPowerHelper::isPowerSource($block)) {
                $power = max($power, BlockPowerHelper::getWeakPower($block, $face));
                continue;
            }

            if (BlockPowerHelper::isNormalBlock($block)) {
                for ($sideFace = 0; $sideFace < 6; $sideFace++) {
                    if ($sideFace == Facing::opposite($face)) continue;

                    $sideBlock = $block->getSide($sideFace);
                    if (!BlockPowerHelper::isPowerSource($sideBlock)) continue;

                    $power = max($power, BlockPowerHelper::getStrongPower($sideBlock, $sideFace));
                }
                continue;
            }

            if ($face == Facing::DOWN) continue;

            if ($block->isTransparent()) {
                if ($face == Facing::UP) {
                    for ($sideFace = 2; $sideFace < 6; $sideFace++) {
                        $sideBlock = $block->getSide($sideFace);
                        if (!$sideBlock instanceof BlockRedstoneWire) continue;

                        $down = $sideBlock->getSide(Facing::DOWN);
                        if (($down instanceof Slab && $down->getSlabType() !== SlabType::DOUBLE()) || $down instanceof Stair) continue;

                        $power = max($power, $sideBlock->getOutputSignalStrength() - 1);
                    }
                    continue;
                }

                $sideBlock = $block->getSide(Facing::DOWN);
                if (!$sideBlock instanceof BlockRedstoneWire) continue;

                $power = max($power, $sideBlock->getOutputSignalStrength() - 1);
            }
        }

        if ($this->getOutputSignalStrength() == $power) return false;

        $this->setOutputSignalStrength($power);
        $this->getPosition()->getWorld()->setBlock($this->getPosition(), $this);
        BlockUpdateHelper::updateAroundStrongRedstone($this);
        return true;
    }

    public function isConnect(int $face): bool {
        return true;
    }
}