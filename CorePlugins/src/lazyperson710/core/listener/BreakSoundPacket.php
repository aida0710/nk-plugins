<?php

namespace lazyperson710\core\listener;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;

class BreakSoundPacket implements Listener {

    /**
     * @param BlockBreakEvent $event
     * @return void
     * @priority LOWEST
     */
    public function onBreak(BlockBreakEvent $event) {
        $player = $event->getPlayer();
        $pk = new PlaySoundPacket();
        $pk->x = $player->getPosition()->getX();
        $pk->y = $player->getPosition()->getY();
        $pk->z = $player->getPosition()->getZ();
        $volume = rand(1, 2);
        $pitch = rand(5, 10);
        $pk->soundName = "random.orb";
        $pk->volume = $volume / 10;
        $pk->pitch = $pitch / 10;
        $player->getNetworkSession()->sendDataPacket($pk);
    }
}