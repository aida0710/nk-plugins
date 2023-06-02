<?php

declare(strict_types = 0);

namespace lazyperson0710\ticket\EventListener;

use lazyperson0710\miningtools\event\MiningToolsBreakEvent;
use lazyperson0710\ticket\TicketAPI;
use lazyperson710\core\packet\SendMessage\SendBroadcastTip;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\player\Player;
use function mt_rand;

class BreakEventListener implements Listener {

    /**
     * @priority MONITOR
     */
    public function onBlockBreak(BlockBreakEvent $event) : void {
        if ($event->isCancelled()) return;
        $probability = '0.125';
        $random = mt_rand(1, 800);
        $this->giveTicket($event->getPlayer(), $random, $probability);
    }

    /**
     * @priority MONITOR
     */
    public function onMiningToolsBlockBreak(MiningToolsBreakEvent $event) : void {
        if ($event->isCancelled()) return;
        $probability = '0.0769';
        $random = mt_rand(1, 1300);
        $this->giveTicket($event->getPlayer(), $random, $probability);
    }

    private function giveTicket(Player $player, int $random, string $probability) : void {
        if ($random === 500) {
            TicketAPI::getInstance()->addTicket($player, 1);
            SendBroadcastTip::Send("Ticketを{$probability}％の確率で{$player->getName()}がゲットしました", 'Ticket');
        }
    }
}