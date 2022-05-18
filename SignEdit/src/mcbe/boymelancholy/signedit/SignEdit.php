<?php

declare(strict_types=1);
namespace mcbe\boymelancholy\signedit;

use mcbe\boymelancholy\signedit\listener\PlayerBlockBreakListener;
use mcbe\boymelancholy\signedit\listener\PlayerInteractListener;
use mcbe\boymelancholy\signedit\listener\PlayerQuitListener;
use mcbe\boymelancholy\signedit\listener\SignEditListener;
use pocketmine\plugin\PluginBase;

class SignEdit extends PluginBase {

    public function onEnable(): void {
        $listeners = [
            new PlayerInteractListener(),
            new PlayerBlockBreakListener(),
            new PlayerQuitListener(),
            new SignEditListener()
        ];
        foreach ($listeners as $listener) {
            $this->getServer()->getPluginManager()->registerEvents($listener, $this);
        }
    }
}