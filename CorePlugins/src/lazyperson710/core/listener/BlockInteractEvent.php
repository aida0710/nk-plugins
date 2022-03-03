<?php

namespace lazyperson710\core\listener;

use lazyperson710\sff\form\TosForm;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\Server;

class BlockInteractEvent implements Listener {

    /**
     * @param PlayerInteractEvent $event
     * @priority LOWEST
     */
    public function onInteract(PlayerInteractEvent $event) {
        if ($event->getBlock()->getPosition()->getWorld()->getDisplayName() == "tos") {
            $player = $event->getPlayer();
            $x = $event->getBlock()->getPosition()->getX();
            $y = $event->getBlock()->getPosition()->getY();
            $z = $event->getBlock()->getPosition()->getZ();
            if ($x === 214 && $y === 106 && $z === 246) {//パスワード解除用石英ブロック
                Server::getInstance()->dispatchCommand($player, "pass");
            }
            if ($x === 215 && $y === 105 && $z === 247) {//TosFormを送る
                $player->sendForm(new TosForm($player));
            }
            if ($x === 232 && $y === 105 && $z === 246) {//TosFormを送る
                $player->sendForm(new TosForm($player));
            }
        }
    }

    public function onBreak(BlockBreakEvent $event) {
        if ($event->getBlock()->getPosition()->getWorld()->getDisplayName() == "tos") {
            $player = $event->getPlayer();
            $x = $event->getBlock()->getPosition()->getX();
            $y = $event->getBlock()->getPosition()->getY();
            $z = $event->getBlock()->getPosition()->getZ();
            if ($x === 214 && $y === 106 && $z === 246) {//パスワード解除用石英ブロック
                Server::getInstance()->dispatchCommand($player, "pass");
            }
        }
    }
}