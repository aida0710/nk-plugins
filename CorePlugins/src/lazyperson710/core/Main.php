<?php

namespace lazyperson710\core;

use lazyperson710\core\command\BookCommand;
use lazyperson710\core\command\DiceCommand;
use lazyperson710\core\command\InvCommand;
use lazyperson710\core\command\MajorCommand;
use lazyperson710\core\task\EffectTaskScheduler;
use lazyperson710\core\task\MotdTask;
use lazyperson710\core\task\ParticleTask;
use lazyperson710\core\task\WorldTimeScheduler;
use pocketmine\inventory\CreativeInventory;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\StringToItemParser;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;

class Main extends PluginBase {

    private static Main $main;
    const ITEM_TURTLE_HELMET = 469;
    const ITEM_GRIND_STONE = -195;

    public function onEnable(): void {
        self::$main = $this;
        $this->getScheduler()->scheduleRepeatingTask(new MotdTask($this->getServer()->getMotd(), '§c>> §bナマケモノ§eサーバー'), 20);
        foreach (scandir("worlds/") as $value) {
            if (is_dir("worlds/" . $value) && ($value !== "." && $value !== "..")) {
                Server::getInstance()->getWorldManager()->loadWorld($value, true);
            }
        }
        $this->getServer()->getPluginManager()->registerEvents(new listener\MessageListener(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new listener\BreakListener(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new listener\Cmdsigns(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new listener\CancelEvent(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new listener\DamageListener(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new listener\DeathListener(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new listener\Major(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new listener\Food(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new listener\FertilizerParticles(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new listener\Elevator(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new listener\DirectInventory(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new listener\WorldChange(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new listener\BlockInteractEvent(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new listener\WorldProtect(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new listener\BreakSoundPacket(), $this);
        $this->getServer()->getCommandMap()->registerAll("core", [
            new MajorCommand(),
            new DiceCommand(),
            new InvCommand(),
            new BookCommand(),
        ]);
        /*Task*/
        $this->getScheduler()->scheduleDelayedTask(new WorldTimeScheduler, 60);
        $this->getScheduler()->scheduleRepeatingTask(new EffectTaskScheduler, 20);
        $this->getScheduler()->scheduleRepeatingTask(new ParticleTask(), 20);
        $this->getScheduler()->scheduleRepeatingTask(new MotdTask($this->getServer()->getMotd(), '§c>> §bナマケモノ§eサーバー'), 200);
    }

}