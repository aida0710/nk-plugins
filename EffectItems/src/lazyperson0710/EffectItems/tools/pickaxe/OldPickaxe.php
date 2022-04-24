<?php

namespace lazyperson0710\EffectItems\tools\pickaxe;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;

class OldPickaxe implements Listener {

    public function onBreak(BlockBreakEvent $event): void {
        if ($event->isCancelled()) return;
        $player = $event->getPlayer();
        $inHand = $player->getInventory()->getItemInHand();
        $namedTag = $player->getInventory()->getItemInHand()->getNamedTag();
        if ($namedTag->getTag('OldPickaxe') !== null) {//OldPickaxe
            if (rand(1, 50) === 50) {
                $player->getInventory()->removeItem($inHand);
                $player->sendTip("つーるがこわれてしまった！！！！");
                $sound = new PlaySoundPacket();
                $sound->soundName = "random.break";
                $sound->x = $player->getPosition()->getX();
                $sound->y = $player->getPosition()->getY();
                $sound->z = $player->getPosition()->getZ();
                $sound->volume = 1;
                $sound->pitch = 1;
                $player->getNetworkSession()->sendDataPacket($sound);
            }
        }
    }
}
