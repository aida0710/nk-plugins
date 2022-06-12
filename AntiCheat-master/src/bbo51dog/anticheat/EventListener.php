<?php

namespace bbo51dog\anticheat;

use bbo51dog\anticheat\model\PlayerDataFactory;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\player\Player;
use pocketmine\Server;

class EventListener implements Listener {

    public function onJoin(PlayerJoinEvent $event) {
        $player = $event->getPlayer();
        if (PlayerDataFactory::getInstance()->existsPlayerData($player)) {
            PlayerDataFactory::getInstance()->getPlayerData($player)->rejoin($player);
        }
    }

    /**
     * @param DataPacketReceiveEvent $event
     * @return void
     * @priority LOWEST
     */
    public function onDataPacketReceive(DataPacketReceiveEvent $event) {
        $player = $event->getOrigin()->getPlayer();
        if (!($player instanceof Player)) {
            return;
        }
        if (!$player->isOnline() || $player->isCreative() || Server::getInstance()->isOp($player->getName())) {
            return;
        }
        if (!PlayerDataFactory::getInstance()->existsPlayerData($player)) {
            PlayerDataFactory::getInstance()->createPlayerData($player);
        }
        PlayerDataFactory::getInstance()->getPlayerData($player)->handlePacket($event->getPacket());
    }
}