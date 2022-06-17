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
        if ($event->getEventName() === (new BlockBreakEvent($player, $event->getBlock(), $player->getInventory()->getItemInHand()))->getEventName()) {
            $probability = "0.02";
            $random = mt_rand(1, 5000);
        } elseif ($event->getEventName() === (new MiningToolsBreakEvent($player, $event->getBlock()))->getEventName()) {
            $probability = "0.0125";
            $random = mt_rand(1, 8000);
        }
        if (empty($random) || empty($probability)) {
            return;
        }
        if ($random === 4500) {
            TicketAPI::getInstance()->addTicket($player, 1);
            if (Server::getInstance()->isOp($player->getName())) {
                $player->sendMessage("§bTicket §7>> §aTicketを{$probability}％の確率で{$player->getName()}がゲットしました(op取得)");
            } else {
                Server::getInstance()->broadcastMessage("§bTicket §7>> §eTicketを{$probability}％の確率で{$player->getName()}がゲットしました");
            }
        }
    }
}