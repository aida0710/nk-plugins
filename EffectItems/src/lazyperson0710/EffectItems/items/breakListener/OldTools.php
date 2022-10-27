<?php

namespace lazyperson0710\EffectItems\items\breakListener;

use lazyperson710\core\packet\SendTip;
use pocketmine\event\block\BlockBreakEvent;

class OldTools {

    public static function execution(BlockBreakEvent $event): void {
        $player = $event->getPlayer();
        $inHand = $player->getInventory()->getItemInHand();
        if (mt_rand(1, 50) === 50) {
            $player->getInventory()->removeItem($inHand);
            SendTip::Send($player, "つーるがこわれてしまった！！！！", "OldTools", true, "random.break");
        }
    }

}
