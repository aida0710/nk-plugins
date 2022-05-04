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
        $this->blockBreakTicket($event);
    }

    /**
     * @param CountBlockEvent $event
     * @priority HIGHEST
     */
    public function onCountEvent(CountBlockEvent $event) {
        $this->blockBreakTicket($event);
    }

    public function blockBreakTicket(Event $event) {
        if (!$event->isCancelled()) {
            $player = $event->getPlayer();
            if (Server::getInstance()->isOp($player->getName())) {
                return;
            }
            $random = rand(1, 5000);
            if ($random === 5000) {
                TicketAPI::getInstance()->addTicket($player, 1);
                /*$ticket = ItemFactory::getInstance()->get(465);
                $ticket->setCustomName("なんか運がいいと思うTicket(?)");
                $ticket->setLore([
                    "lore1" => "常設イベント専用ガチャチケット",
                    "lore2" => "/gatyaでガチャを引いてみよう！",
                ]);
                if ($player->getInventory()->canAddItem($ticket)) {
                    $player->getInventory()->addItem($ticket);
                } else {
                    $player->getWorld()->dropItem($player->getPosition(), $ticket);
                    $player->sendMessage("§bTicket §7>> §aインベントリに空きが無いためTicketはドロップされました");
                }*/
                Server::getInstance()->broadcastMessage("§bTicket §7>> §eTicketを{$player->getName()}がゲットしました！確率:1/5000");
            }
        }
    }
}