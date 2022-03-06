<?php

namespace tedo0627\redstonecircuit\block\mechanism;

use pocketmine\block\ActivatorRail;
use pocketmine\block\utils\RailConnectionInfo;
use pocketmine\item\Item;
use pocketmine\math\Facing;
use pocketmine\player\Player;
use tedo0627\redstonecircuit\block\BlockPowerHelper;
use tedo0627\redstonecircuit\block\IRedstoneComponent;
use tedo0627\redstonecircuit\block\RedstoneComponentTrait;

class BlockActivatorRail extends ActivatorRail implements IRedstoneComponent {
    use RedstoneComponentTrait;

    public function onPostPlace(): void {
        parent::onPostPlace();
        $this->updatePower($this);
        $this->updateConnectedRails();
    }

    public function onBreak(Item $item, ?Player $player = null): bool {
        parent::onBreak($item, $player);
        $this->updateConnectedRails();
        return true;
    }

    public function onRedstoneUpdate(): void {
        $this->updatePower($this);
        $this->updateConnectedRails();
    }

    protected function updateConnectedRails(): void {
        $connections = $this->getCurrentShapeConnections();
        for ($i = 0; $i < count($connections); $i++) {
            $face = $connections[$i];
            $up = false;
            if (($face & RailConnectionInfo::FLAG_ASCEND) > 0) {
                $face = $face ^ RailConnectionInfo::FLAG_ASCEND;
                $up = true;
            }

            $side = $this;
            for ($j = 0; $j < 8; $j++) {
                $side = $side->getSide($face);
                if ($up) $side = $side->getSide(Facing::UP);
                if (!$side instanceof ActivatorRail) {
                    $side = $side->getSide(Facing::DOWN);
                    if (!$side instanceof ActivatorRail) break;
                }

                $faces = $side->getCurrentShapeConnections();
                if (in_array($face, $faces, true)) {
                    $this->updatePower($side);
                    $up = false;
                    continue;
                }

                if (in_array($face | RailConnectionInfo::FLAG_ASCEND, $faces, true)) {
                    $this->updatePower($side);
                    $up = true;
                    continue;
                }
                break;
            }
        }
    }

    protected function updatePower(ActivatorRail $block): void {
        if (BlockPowerHelper::isPowered($block)) {
            $block->setPowered(true);
            $block->getPosition()->getWorld()->setBlock($block->getPosition(), $block);
            return;
        }

        $connections = $block->getCurrentShapeConnections();
        for ($i = 0; $i < count($connections); $i++) {
            $face = $connections[$i];
            $up = false;
            if (($face & RailConnectionInfo::FLAG_ASCEND) > 0) {
                $face = $face ^ RailConnectionInfo::FLAG_ASCEND;
                $up = true;
            }

            $side = $block;
            for ($j = 0; $j < 8; $j++) {
                $side = $side->getSide($face);
                if ($up) $side = $side->getSide(Facing::UP);
                if (!$side instanceof ActivatorRail) {
                    $side = $side->getSide(Facing::DOWN);
                    if (!$side instanceof ActivatorRail) break;
                }

                $faces = $side->getCurrentShapeConnections();
                if (in_array($face, $faces, true)) {
                    if (BlockPowerHelper::isPowered($side)) {
                        $block->setPowered(true);
                        $block->getPosition()->getWorld()->setBlock($block->getPosition(), $block);
                        return;
                    }
                    $up = false;
                    continue;
                }

                if (in_array($face | RailConnectionInfo::FLAG_ASCEND, $faces, true)) {
                    if (BlockPowerHelper::isPowered($side)) {
                        $block->setPowered(true);
                        $block->getPosition()->getWorld()->setBlock($block->getPosition(), $block);
                        return;
                    }
                    $up = true;
                    continue;
                }
                break;
            }
        }

        $block->setPowered(false);
        $block->getPosition()->getWorld()->setBlock($block->getPosition(), $block);
    }
}