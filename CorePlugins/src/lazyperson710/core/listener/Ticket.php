<?php

namespace lazyperson710\core\listener;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\item\ItemFactory;
use pocketmine\Server;

class Ticket implements Listener {

    /**
     * @param BlockBreakEvent $event
     * @priority HIGHEST
     */
    public function onBreak(BlockBreakEvent $event) {
        $player = $event->getPlayer();
        $name = $player->getName();
        if (!$event->isCancelled()) {
            $random = rand(1, 5000);
            if (Server::getInstance()->isOp($name)) {
                return;
            }
            if ($random === 5000) {
                $ticket = ItemFactory::getInstance()->get(465);
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
                }
                Server::getInstance()->broadcastMessage("§bTicket §7>> §eTicketを{$name}がゲットしました！確率:1/5000");
            }
        }
    }

}