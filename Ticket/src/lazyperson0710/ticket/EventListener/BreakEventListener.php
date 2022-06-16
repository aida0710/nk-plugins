<?php

namespace lazyperson0710\ticket\EventListener;

use deceitya\miningtools\event\MiningToolsBreakEvent;
use lazyperson0710\ticket\TicketAPI;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\Server;

class BreakEventListener implements Listener {

    /**
     * @param BlockBreakEvent $event
     * @priority HIGHEST
     */
    public function onBreak(BlockBreakEvent $event) {
        if ($event->isCancelled()) return;
        $this->blockBreakTicket($event);
    }

    /**
     * @param MiningToolsBreakEvent $event
     * @priority HIGHEST
     */
    public function onCountEvent(MiningToolsBreakEvent $event) {
        if ($event->isCancelled()) return;
        $this->blockBreakTicket($event);
    }

    public function blockBreakTicket(BlockBreakEvent|MiningToolsBreakEvent $event) {
        $player = $event->getPlayer();
        if (Server::getInstance()->isOp($player->getName())) {
            return;
        }
        if ($event->getEventName() === "MiningToolsBreakEvent") {
            $random = mt_rand(1, 10000);
        } elseif ($event->getEventName() === "BlockBreakEvent") {
            $random = mt_rand(1, 5000);
        } else {
            echo "error";
        }
        if (empty($random)) {
            echo "aaaaaaaaa";
            return;
        }
        if ($random === 4500) {
            TicketAPI::getInstance()->addTicket($player, 1);
            Server::getInstance()->broadcastMessage("§bTicket §7>> §eTicketを{$player->getName()}がゲットしました！確率:1/5000");
        }
    }
}