<?php

declare(strict_types = 0);

namespace lazyperson0710\WorldManagement\EventListener;

use lazyperson0710\WorldManagement\database\WorldManagementAPI;
use lazyperson710\core\packet\SendMessage\SendTip;
use pocketmine\block\VanillaBlocks;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;
use pocketmine\Server;
use function in_array;

class ResourceWorldProtect implements Listener {

    /**
     * @return void
     * @priority Low
     */
    public function onBreak(BlockBreakEvent $event) {
        if ($event->getBlock()->getPosition()->getWorld()->getFolderName() === 'resource') {
            $blocks = [
                VanillaBlocks::STONE()->getName(),
                VanillaBlocks::GRANITE()->getName(),
                VanillaBlocks::DIORITE()->getName(),
                VanillaBlocks::ANDESITE()->getName(),
                VanillaBlocks::OAK_LOG()->getName(),
                VanillaBlocks::SPRUCE_LOG()->getName(),
                VanillaBlocks::BIRCH_LOG()->getName(),
                VanillaBlocks::JUNGLE_LOG()->getName(),
                VanillaBlocks::ACACIA_LOG()->getName(),
                VanillaBlocks::DARK_OAK_LOG()->getName(),
            ];
            $heightLimit = WorldManagementAPI::getInstance()->getHeightLimit('resource');
            if ($event->getBlock()->getPosition()->getFloorY() >= $heightLimit) {
                SendTip::Send($event->getPlayer(), "現在のワールドではY.{$heightLimit}以上のブロックを破壊することは許可されていません", 'Protect', false);
                if (Server::getInstance()->isOp($event->getPlayer()->getName())) {
                    return;
                }
                $event->cancel();
                return;
            }
            if (!in_array($event->getBlock()->getName(), $blocks, true)) {
                SendTip::Send($event->getPlayer(), "現在のワールドでは{$event->getBlock()->getName()}の破壊は許可されていません", 'Protect', false);
                if (Server::getInstance()->isOp($event->getPlayer()->getName())) {
                    return;
                }
                $event->cancel();
            }
        }
    }

    /**
     * @return void
     * @priority Low
     */
    public function onPlace(BlockPlaceEvent $event) {
        $heightLimit = WorldManagementAPI::getInstance()->getHeightLimit('resource');
        if ($event->getBlock()->getPosition()->getWorld()->getFolderName() === 'resource') {
            $blocks = [
                VanillaBlocks::STONE()->getName(),
                VanillaBlocks::GRANITE()->getName(),
                VanillaBlocks::DIORITE()->getName(),
                VanillaBlocks::ANDESITE()->getName(),
                VanillaBlocks::OAK_LOG()->getName(),
                VanillaBlocks::SPRUCE_LOG()->getName(),
                VanillaBlocks::BIRCH_LOG()->getName(),
                VanillaBlocks::JUNGLE_LOG()->getName(),
                VanillaBlocks::ACACIA_LOG()->getName(),
                VanillaBlocks::DARK_OAK_LOG()->getName(),
            ];
            if ($event->getBlock()->getPosition()->getFloorY() >= $heightLimit) {
                SendTip::Send($event->getPlayer(), "現在のワールドではY.{$heightLimit}以上でブロックを設置することは許可されていません", 'Protect', false);
                if (Server::getInstance()->isOp($event->getPlayer()->getName())) {
                    return;
                }
                $event->cancel();
                return;
            }
            if (!in_array($event->getBlock()->getName(), $blocks, true)) {
                SendTip::Send($event->getPlayer(), "現在のワールドでは{$event->getBlock()->getName()}の設置は許可されていません", 'Protect', false);
                if (Server::getInstance()->isOp($event->getPlayer()->getName())) {
                    return;
                }
                $event->cancel();
            }
        }
    }

}
