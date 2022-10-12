<?php

namespace lazyperson0710\ticket\EventListener;

use lazyperson0710\ticket\TicketAPI;
use lazyperson710\core\packet\SoundPacket;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

class JoinEventListener implements Listener {

    /**
     * @param PlayerJoinEvent $event
     * @priority HIGHEST
     */
    public function onJoin(PlayerJoinEvent $event) {
        $player = $event->getPlayer();
        if (TicketAPI::getInstance()->dataExists($player) !== true) {
            TicketAPI::getInstance()->createData($event->getPlayer());
        }
        if ((TicketAPI::getInstance()->replaceInventoryTicket($player) + TicketAPI::getInstance()->replaceStackStorageTicket($player)) >= 1) {
            $player->sendMessage("§bTicket §7>> §aチケットの変換処理を実行しました");
            SoundPacket::Send($player, 'note.harp');
        }
    }
}