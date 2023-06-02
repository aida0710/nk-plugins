<?php

declare(strict_types = 0);

namespace lazyperson710\core\listener;

use lazyperson710\core\packet\SendForm;
use lazyperson710\core\packet\SoundPacket;
use lazyperson710\sff\form\TosForm;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\Server;

class PassBlockInteract implements Listener {

    /**
     * @priority LOWEST
     */
    public function onInteract(PlayerInteractEvent $event) : void {
        if ($event->getBlock()->getPosition()->getWorld()->getDisplayName() == 'tos') {
            $player = $event->getPlayer();
            $x = $event->getBlock()->getPosition()->getFloorX();
            $y = $event->getBlock()->getPosition()->getFloorY();
            $z = $event->getBlock()->getPosition()->getFloorZ();
            if ($x === 214 && $y === 106 && $z === 246) {
                Server::getInstance()->dispatchCommand($player, 'pass');
            }
            if ($x === 215 && $y === 105 && $z === 247) {
                SendForm::Send($player, (new TosForm()));
                SoundPacket::Send($player, 'note.harp');
            }
            if ($x === 232 && $y === 105 && $z === 246) {
                SendForm::Send($player, (new TosForm()));
                SoundPacket::Send($player, 'note.harp');
            }
        }
    }

    public function onBreak(BlockBreakEvent $event) : void {
        if ($event->getBlock()->getPosition()->getWorld()->getDisplayName() == 'tos') {
            $player = $event->getPlayer();
            $x = $event->getBlock()->getPosition()->getFloorX();
            $y = $event->getBlock()->getPosition()->getFloorY();
            $z = $event->getBlock()->getPosition()->getFloorZ();
            if ($x === 214 && $y === 106 && $z === 246) {
                Server::getInstance()->dispatchCommand($player, 'pass');
            }
        }
    }
}
