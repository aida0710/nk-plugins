<?php

namespace Deceitya\MiningLevel;

use Deceitya\MiningLevel\Event\EventListener;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;

class MiningLevelSystem extends PluginBase {

    public function onEnable(): void {
        $this->saveResource('config.yml');
        MiningLevelAPI::getInstance()->init($this);
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this->getConfig()), $this);
        $this->getScheduler()->scheduleRepeatingTask(new ClosureTask(
            function (): void {
                MiningLevelAPI::getInstance()->writecache();
            }
        ), 20 * 60);
    }

    public function onDisable(): void {
        MiningLevelAPI::getInstance()->deinit();
    }

}
