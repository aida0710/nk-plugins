<?php

namespace lazyperson0710\WorldManagement;

use lazyperson0710\WorldManagement\blocks\FarmlandBlock;
use lazyperson0710\WorldManagement\command\WpCommand;
use lazyperson0710\WorldManagement\database\WorldManagementAPI;
use lazyperson0710\WorldManagement\EventListener\CancelItemUseEvent;
use lazyperson0710\WorldManagement\EventListener\PlayerDamageEvent;
use lazyperson0710\WorldManagement\EventListener\PlayerTeleportEvent;
use lazyperson0710\WorldManagement\EventListener\ResourceWorldProtect;
use lazyperson0710\WorldManagement\EventListener\StopHunger;
use lazyperson0710\WorldManagement\EventListener\WorldProtect;
use lazyperson0710\WorldManagement\EventListener\YachimataCityWorldProtect;
use lazyperson0710\WorldManagement\task\WorldLevelCheckTask;
use lazyperson0710\WorldManagement\task\WorldTimeScheduler;
use lazyperson0710\WorldManagement\WorldLimit\task\CheckLifeWorldTask;
use lazyperson0710\WorldManagement\WorldLimit\task\CheckPositionTask;
use lazyperson0710\WorldManagement\WorldLimit\WorldProperty;
use pocketmine\block\BlockBreakInfo;
use pocketmine\block\BlockFactory;
use pocketmine\block\BlockIdentifier;
use pocketmine\block\BlockToolType;
use pocketmine\block\VanillaBlocks;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;

class Main extends PluginBase {

    public const CHECK_INTERVAL = 16;
    public const TELEPORT_INTERVAL = 15;

    protected function onEnable(): void {
        foreach (scandir("worlds/") as $value) {
            if (is_dir("worlds/" . $value) && ($value !== "." && $value !== "..")) {
                Server::getInstance()->getWorldManager()->loadWorld($value, true);
            }
        }
        WorldManagementAPI::getInstance()->init();
        $this->getServer()->getPluginManager()->registerEvents(new CancelItemUseEvent(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new PlayerDamageEvent(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new PlayerTeleportEvent(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new ResourceWorldProtect(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new StopHunger(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new WorldProtect(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new YachimataCityWorldProtect(), $this);
        $this->getScheduler()->scheduleDelayedTask(new WorldTimeScheduler, 60);
        BlockFactory::getInstance()->register(new FarmlandBlock(new BlockIdentifier(VanillaBlocks::FARMLAND()->getId(), 0), "Farmland", new BlockBreakInfo(0.6, BlockToolType::SHOVEL)), true);
        $this->getServer()->getCommandMap()->registerAll("worldManagement", [
            new WpCommand(),
        ]);
        $worlds = [];
        $worldApi = WorldManagementAPI::getInstance();
        foreach (Server::getInstance()->getWorldManager()->getWorlds() as $world) {
            $world = $world->getFolderName();
            $worlds[] = new WorldProperty($world, $worldApi->getWorldLimitX_1($world), $worldApi->getWorldLimitX_2($world), $worldApi->getWorldLimitZ_1($world), $worldApi->getWorldLimitZ_2($world));
        }
        $this->getScheduler()->scheduleRepeatingTask(new CheckPositionTask($this->getScheduler(), $worlds), self::CHECK_INTERVAL * 20);
        $this->getScheduler()->scheduleRepeatingTask(new CheckLifeWorldTask($worlds), 20);
        $this->getScheduler()->scheduleRepeatingTask(new WorldLevelCheckTask(), 20);
    }

}
