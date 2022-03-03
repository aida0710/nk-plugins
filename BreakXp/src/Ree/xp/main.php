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

    public function onBreak(BlockBreakEvent $ev) {
        $this->getScheduler()->scheduleDelayedTask(new CheckTask($ev, $ev->getXpDropAmount()), 3);
    }
}

class CheckTask extends Task {

    /**
     * @var BlockBreakEvent
     */
    private $ev;

    public function __construct($ev, $xp) {
        $this->ev = $ev;
        $this->xp = $xp;
    }

    public function onRun(): void {
        if (!$this->ev->isCancelled()) {
            switch ($this->ev->getBlock()->getId()) {
                case 52:
                    $setxp = 5000;
                    $this->ev->getPlayer()->getXpManager()->addXp($setxp);
                    break;
            }
        }
    }
}