<?php

namespace lazyperson0710\WorldManagement;

use lazyperson0710\WorldManagement\command\TestCommand;
use lazyperson0710\WorldManagement\database\WorldManagementAPI;
use lazyperson0710\WorldManagement\EventListener\CancelItemUseEvent;
use lazyperson0710\WorldManagement\EventListener\PlayerDamageEvent;
use lazyperson0710\WorldManagement\EventListener\PlayerTeleportEvent;
use lazyperson0710\WorldManagement\EventListener\StopHunger;
use lazyperson0710\WorldManagement\EventListener\WorldProtect;
use lazyperson0710\WorldManagement\task\WorldTimeScheduler;
use lazyperson0710\WorldManagement\WorldLimit\task\CheckPositionTask;
use lazyperson0710\WorldManagement\WorldLimit\WorldProperty;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;

class Main extends PluginBase {

    public const CHECK_INTERVAL = 30;
    public const TELEPORT_INTERVAL = 15;

    protected function onEnable(): void {
        WorldManagementAPI::getInstance()->init();
        foreach (scandir("worlds/") as $value) {
            if (is_dir("worlds/" . $value) && ($value !== "." && $value !== "..")) {
                Server::getInstance()->getWorldManager()->loadWorld($value, true);
            }
        }
        $this->getServer()->getPluginManager()->registerEvents(new CancelItemUseEvent(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new PlayerDamageEvent(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new PlayerTeleportEvent(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new StopHunger(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new WorldProtect(), $this);
        $this->getScheduler()->scheduleDelayedTask(new WorldTimeScheduler, 60);
        $this->getServer()->getCommandMap()->registerAll("worldManagement", [
            new TestCommand(),
        ]);
        $worlds = [];
        $worldApi = WorldManagementAPI::getInstance();
        foreach (Server::getInstance()->getWorldManager()->getWorlds() as $world) {
            $world = $world->getFolderName();
            $worlds[] = new WorldProperty($world, $worldApi->getWorldLimitX_1($world), $worldApi->getWorldLimitX_2($world), $worldApi->getWorldLimitZ_1($world), $worldApi->getWorldLimitZ_2($world));
        }
        $this->getScheduler()->scheduleRepeatingTask(new CheckPositionTask($this->getScheduler(), $worlds), self::CHECK_INTERVAL * 20);
    }
}
