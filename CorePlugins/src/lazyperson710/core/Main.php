<?php

namespace lazyperson710\core;

use lazyperson710\core\command\BookCommand;
use lazyperson710\core\command\DiceCommand;
use lazyperson710\core\command\FlyCommand;
use lazyperson710\core\command\InvCommand;
use lazyperson710\core\command\MajorCommand;
use lazyperson710\core\task\FlyCheckTask;
use lazyperson710\core\task\ParticleTask;
use pocketmine\inventory\ArmorInventory;
use pocketmine\inventory\CreativeInventory;
use pocketmine\item\Armor;
use pocketmine\item\ArmorTypeInfo;
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
            new FlyCommand(),
        ]);
        $this->getScheduler()->scheduleDelayedTask(new TaskScheduler, 60);
        $this->getScheduler()->scheduleRepeatingTask(new TimeScheduler, 20);
        $this->getScheduler()->scheduleRepeatingTask(new ParticleTask(), 20);
        $this->getScheduler()->scheduleRepeatingTask(new FlyCheckTask(), 1200);
        ItemFactory::getInstance()->register(new Item(new ItemIdentifier(self::ITEM_GRIND_STONE, 0), 'Login Bonus'));
        StringToItemParser::getInstance()->register('grindstone', fn(string $input) => new Item(new ItemIdentifier(self::ITEM_GRIND_STONE, 0), 'Login Bonus'));
        CreativeInventory::getInstance()->add(new Item(new ItemIdentifier(self::ITEM_GRIND_STONE, 0), 'Login Bonus'));
        ItemFactory::getInstance()->register(new Armor(new ItemIdentifier(self::ITEM_TURTLE_HELMET, 0), 'Turtle Helmet', new ArmorTypeInfo(2, 275, ArmorInventory::SLOT_HEAD)));
        StringToItemParser::getInstance()->register('turtle_helmet', fn(string $input) => new Armor(new ItemIdentifier(self::ITEM_TURTLE_HELMET, 0), 'Turtle Helmet', new ArmorTypeInfo(2, 275, ArmorInventory::SLOT_HEAD)));
        CreativeInventory::getInstance()->add(new Armor(new ItemIdentifier(self::ITEM_TURTLE_HELMET, 0), 'Turtle Helmet', new ArmorTypeInfo(2, 275, ArmorInventory::SLOT_HEAD)));
    }

    public static function getInstance(): Main {
        return self::$main;
    }

}