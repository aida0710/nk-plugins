<?php

declare(strict_types = 0);

namespace Deceitya\MiningLevel;

use Deceitya\MiningLevel\Event\EventListener;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;

class MiningLevelSystem extends PluginBase {

    protected function onDisable() : void {
        MiningLevelAPI::getInstance()->deinit();
    }

    protected function onEnable() : void {
        MiningLevelAPI::getInstance()->init($this);
        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
        $this->getScheduler()->scheduleRepeatingTask(new ClosureTask(
            function () : void {
                MiningLevelAPI::getInstance()->writecache();
            }
        ), 20 * 60 * 8);
    }

}
