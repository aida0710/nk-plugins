<?php

namespace lazyperson710\core\listener;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\Server;

class WorldProtect implements Listener {

    public function onBreak(BlockBreakEvent $event) {
        if ($event->isCancelled()) {
            return;
        }
        if (!Server::getInstance()->isOp($event->getPlayer()->getName())) {
            switch ($event->getPlayer()->getPosition()->getWorld()->getFolderName()) {
                case "tos":
                case "lobby":
                case "athletic":
                case "event-1":
                case "event-2":
                case "event-3":
                case "event-4":
                case "event-5":
                    $event->cancel();
                    $event->getPlayer()->sendTip("§bProtect §7>> §cこのワールドは保護されています");
            }
        }
    }

    public function onInteract(PlayerInteractEvent $event) {
        if ($event->isCancelled()) {
            return;
        }
        if (!Server::getInstance()->isOp($event->getPlayer()->getName())) {
            switch ($event->getPlayer()->getPosition()->getWorld()->getFolderName()) {
                case "tos":
                case "lobby":
                case "athletic":
                case "event-1":
                case "event-2":
                case "event-3":
                case "event-4":
                case "event-5":
                    $event->cancel();
                    $event->getPlayer()->sendTip("§bProtect §7>> §cこのワールドは保護されています");
            }
        }
    }
}