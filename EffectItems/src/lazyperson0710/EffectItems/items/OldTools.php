<?php

namespace lazyperson0710\EffectItems\items;

use lazyperson0710\EffectItems\packet\SoundPacket;
use pocketmine\event\block\BlockBreakEvent;

class OldTools {

    public static function init(BlockBreakEvent $event): void {
        $player = $event->getPlayer();
        $inHand = $player->getInventory()->getItemInHand();
        if (mt_rand(1, 50) === 50) {
            $player->getInventory()->removeItem($inHand);
            $player->sendTip("つーるがこわれてしまった！！！！");
            SoundPacket::init($player, "random.break");
        }
    }

}
