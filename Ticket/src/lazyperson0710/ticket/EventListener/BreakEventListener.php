<?php

namespace lazyperson0710\ticket\EventListener;

use deceitya\miningtools\event\CountBlockEvent;
use lazyperson0710\ticket\TicketAPI;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Event;
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
     * @param CountBlockEvent $event
     * @priority HIGHEST
     */
    public function onCountEvent(CountBlockEvent $event) {
        if ($event->isCancelled()) return;
        $this->blockBreakTicket($event);
    }

    public function blockBreakTicket(Event $event) {
        $player = $event->getPlayer();
        if (Server::getInstance()->isOp($player->getName())) {
            return;
        }
        $random = mt_rand(1, 5000);
        if ($random === 5000) {
            TicketAPI::getInstance()->addTicket($player, 1);
            Server::getInstance()->broadcastMessage("§bTicket §7>> §eTicketを{$player->getName()}がゲットしました！確率:1/5000");
        }
    }
}