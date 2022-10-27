<?php

namespace lazyperson0710\WorldManagement\EventListener;

use lazyperson0710\WorldManagement\database\WorldCategory;
use lazyperson0710\WorldManagement\database\WorldManagementAPI;
use lazyperson710\core\packet\SendTip;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\Server;

class WorldProtect implements Listener {

    public function onPlace(BlockPlaceEvent $event) {
        if ($event->isCancelled()) {
            return;
        }
        $heightLimit = WorldManagementAPI::getInstance()->getHeightLimit($event->getPlayer()->getWorld()->getFolderName());
        if ($event->getBlock()->getPosition()->getFloorY() >= $heightLimit) {
            SendTip::Send($event->getPlayer(), "現在のワールドではY.{$heightLimit}以上でブロックを設置することは許可されていません", "Protect", false);
            if (!Server::getInstance()->isOp($event->getPlayer()->getName())) {
                $event->cancel();
            }
            return;
        }
        $this->PlayerAction($event);
    }

    public function onBreak(BlockBreakEvent $event) {
        if ($event->isCancelled()) {
            return;
        }
        $this->PlayerAction($event);
    }

    public function onInteract(PlayerInteractEvent $event) {
        if ($event->isCancelled()) {
            return;
        }
        if (in_array($event->getBlock()->getPosition()->getWorld()->getFolderName(), WorldCategory::PVP)) {
            return;
        }
        $this->PlayerAction($event);
    }

    public function PlayerAction(BlockBreakEvent|BlockPlaceEvent|PlayerInteractEvent $event) {
        if (!Server::getInstance()->isOp($event->getPlayer()->getName())) {
            $worldName = $event->getPlayer()->getPosition()->getWorld()->getFolderName();
            if (in_array($worldName, WorldCategory::PublicWorld) || in_array($worldName, WorldCategory::PublicEventWorld) || in_array($worldName, WorldCategory::PVP)) {
                if (!Server::getInstance()->isOp($event->getPlayer()->getName())) {
                    $event->cancel();
                }
                SendTip::Send($event->getPlayer(), "このワールドは保護されています", "Protect", false);
            }
        }
    }

}