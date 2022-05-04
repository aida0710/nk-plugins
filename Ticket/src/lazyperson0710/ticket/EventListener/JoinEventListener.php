<?php

namespace lazyperson0710\ticket\EventListener;

use deceitya\miningtools\event\CountBlockEvent;
use lazyperson0710\ticket\TicketAPI;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Event;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\Server;

class JoinEventListener implements Listener {

    /**
     * @param PlayerJoinEvent $event
     * @priority HIGHEST
     */
    public function onBreak(PlayerJoinEvent $event) {
        $result = TicketAPI::getInstance()->replaceTicket($event->getPlayer());
        if ($result === false) return;
        $event->getPlayer()->sendMessage("§bTicket §7>> §a{$result}個のTicketを交換しました");
    }
}