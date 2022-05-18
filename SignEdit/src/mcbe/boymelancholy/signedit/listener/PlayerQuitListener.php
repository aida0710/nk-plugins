<?php

declare(strict_types=1);
namespace mcbe\boymelancholy\signedit\listener;

use mcbe\boymelancholy\signedit\util\InteractFlag;
use mcbe\boymelancholy\signedit\util\TextClipboard;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;

class PlayerQuitListener implements Listener {

    public function onPlayerQuit(PlayerQuitEvent $event) {
        $player = $event->getPlayer();
        TextClipboard::deleteClipboard($player);
        InteractFlag::delete($player);
    }
}