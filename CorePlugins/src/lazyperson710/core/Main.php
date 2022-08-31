<?php

namespace lazyperson710\core;

use lazyperson710\core\command\BookCommand;
use lazyperson710\core\command\DiceCommand;
use lazyperson710\core\command\JoinItemCommand;
use lazyperson710\core\command\MajorCommand;
use lazyperson710\core\command\WarpPVPCommand;
use lazyperson710\core\task\EffectTaskScheduler;
use lazyperson710\core\task\EntityRemoveTask;
use lazyperson710\core\task\MotdTask;
use lazyperson710\core\task\ParticleTask;
use pocketmine\block\BlockBreakInfo;
use pocketmine\block\BlockFactory;
use pocketmine\block\BlockIdentifier;
use pocketmine\block\BlockLegacyIds;
use pocketmine\block\TNT;
use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\data\bedrock\EnchantmentIds;
use pocketmine\inventory\CreativeInventory;
use pocketmine\item\CookedChicken;
use pocketmine\item\CookedMutton;
use pocketmine\item\CookedSalmon;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\ItemFlags;
use pocketmine\item\enchantment\Rarity;
use pocketmine\item\enchantment\StringToEnchantmentParser;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\StringToItemParser;
use pocketmine\item\VanillaItems;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase {

    public const ITEM_GRIND_STONE = -195;
    public const EntityRemoveTaskInterval = 60;
    public int $entityRemoveTimeLeft;
    private static Main $main;

    public function onEnable(): void {
        self::$main = $this;
        $this->entityRemoveTimeLeft = self::EntityRemoveTaskInterval;
        $enchant = new Enchantment('幸運', Rarity::RARE, ItemFlags::DIG, ItemFlags::SHEARS, 3);
        EnchantmentIdMap::getInstance()->register(EnchantmentIds::FORTUNE, $enchant);
        StringToEnchantmentParser::getInstance()->register("fortune", fn() => $enchant);
        /*PlayerEventListener*/
        $this->getServer()->getPluginManager()->registerEvents(new listener\MessageListener(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new listener\BreakListener(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new listener\CmdSigns(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new listener\CancelEvent(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new listener\DeathEventListener(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new listener\Major(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new listener\Elevator(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new listener\DirectInventory(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new listener\JoinPlayerEvent(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new listener\JoinItemUseEvent(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new listener\PassBlockInteract(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new listener\BreakSoundPacket(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new listener\GeneralEventListener(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new listener\VanillaPickBlock(), $this);
        /*Items*/
        ItemFactory::getInstance()->register(new Item(new ItemIdentifier(self::ITEM_GRIND_STONE, 0), 'Login Bonus'));
        CreativeInventory::getInstance()->add(new Item(new ItemIdentifier(self::ITEM_GRIND_STONE, 0), 'Login Bonus'));
        StringToItemParser::getInstance()->register('grindstone', fn(string $input) => new Item(new ItemIdentifier(self::ITEM_GRIND_STONE, 0), 'Login Bonus'));
        /*Command*/
        $this->getServer()->getCommandMap()->registerAll("core", [
            new MajorCommand(),
            new DiceCommand(),
            new JoinItemCommand(),
            new BookCommand(),
            new WarpPVPCommand(),
        ]);
        /*Task*/
        $this->getScheduler()->scheduleRepeatingTask(new EffectTaskScheduler, 20);
        $this->getScheduler()->scheduleRepeatingTask(new ParticleTask(), 20);
        $this->getScheduler()->scheduleRepeatingTask(new EntityRemoveTask(), 20);
        $this->getScheduler()->scheduleRepeatingTask(new MotdTask($this->getServer()->getMotd(), '§c>> §bナマケモノ§eサーバー'), 200);
    }

    public static function getInstance(): Main {
        return self::$main;
    }

}