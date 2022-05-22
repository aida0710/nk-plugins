<?php

namespace lazyperson0710\EffectItems\tools\Axe;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\item\Durable;

class QuadAxe implements Listener {

    public function onBreak(BlockBreakEvent $event) {
        if ($event->isCancelled()) return;
        $player = $event->getPlayer();
        $inHand = $player->getInventory()->getItemInHand();
        if ($inHand->getNamedTag()->getTag('QuadAxe') !== null) {//QuadAxe
            if ($inHand instanceof Durable) {
                foreach ($event->getDrops() as $drop) {
                    switch ($drop->getId()) {
                        case 17:
                        case 162:
                            $drop->setCount($rand = mt_rand(2, 10));
                            $inHand->applyDamage($rand * 2);
                            $player->getInventory()->setItemInHand($inHand);
                            $player->sendTip("原木が{$rand}個に増えた！！！");
                            break;
                        default:
                            return;
                    }
                }
            }
        }
    }
}
