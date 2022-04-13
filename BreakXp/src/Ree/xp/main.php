<?php

namespace Ree\xp;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\Task;

class main extends PluginBase implements Listener {

    const addxp = 0;

    public function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onBreak(BlockBreakEvent $event) {
        $this->getScheduler()->scheduleDelayedTask(new CheckTask($event, $event->getXpDropAmount()), 3);
    }
}

class CheckTask extends Task {

    /**
     * @var BlockBreakEvent
     */
    private $event;
    private $experience;

    public function __construct($event, $experience) {
        $this->event = $event;
        $this->experience = $experience;
    }

    public function onRun(): void {
        if ($this->event->isCancelled()) return;
        if ($this->event->getBlock()->getId() == 52) {
            $setxp = 5000;
            $this->event->getPlayer()->getXpManager()->addXp($setxp);
        }
    }
}