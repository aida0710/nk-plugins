<?php

declare(strict_types=1);
namespace mcbe\boymelancholy\signedit\listener;

use mcbe\boymelancholy\signedit\event\BreakSignEvent;
use mcbe\boymelancholy\signedit\event\InteractSignEvent;
use mcbe\boymelancholy\signedit\form\BreakForm;
use mcbe\boymelancholy\signedit\form\HomeForm;
use pocketmine\event\Listener;
use pocketmine\Server;

class SignEditListener implements Listener {

    public function onInteractSign(InteractSignEvent $event) {
        if (!Server::getInstance()->isOp($event->getPlayer()->getName())) return;
        $player = $event->getPlayer();
        $sign = $event->getSign();
        $player->sendForm(new HomeForm($sign));
    }

    public function onBreakSign(BreakSignEvent $event) {
        if (!Server::getInstance()->isOp($event->getPlayer()->getName())) return;
        $player = $event->getPlayer();
        $sign = $event->getSign();
        $player->sendForm(new BreakForm($sign));
    }
}