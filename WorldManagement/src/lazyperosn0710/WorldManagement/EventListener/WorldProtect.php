<?php

namespace lazyperson0710\WorldManagement\EventListener;

use lazyperson0710\WorldManagement\database\WorldCategory;
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
        $this->PlayerAction($event);
    }

    public function PlayerAction(BlockBreakEvent|BlockPlaceEvent|PlayerInteractEvent $event) {
        if (!Server::getInstance()->isOp($event->getPlayer()->getName())) {
            $worldName = $event->getPlayer()->getPosition()->getWorld()->getFolderName();
            if (in_array($worldName, WorldCategory::PublicWorld) || in_array($worldName, WorldCategory::PublicEventWorld)) {
                $event->cancel();
                $event->getPlayer()->sendTip("§bProtect §7>> §cこのワールドは保護されています");
            }
        }
    }

}