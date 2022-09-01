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
use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\data\bedrock\EnchantmentIds;
use pocketmine\inventory\CreativeInventory;
use pocketmine\item\CookedChicken;
use pocketmine\item\CookedFish;
use pocketmine\item\CookedMutton;
use pocketmine\item\CookedPorkchop;
use pocketmine\item\CookedRabbit;
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
    public const ITEM_CHISELED_NETHER_BRICKS = -302;
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
        $this->getServer()->getPluginManager()->registerEvents(new listener\DropItemSetDeleteTime(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new listener\FortuneListener(), $this);
        /*Items*/
        $this->defaultItemNameChange();
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

    private function defaultItemNameChange(): void {
        ItemFactory::getInstance()->register(new Item(new ItemIdentifier(self::ITEM_GRIND_STONE, 0), 'Login Bonus'));
        CreativeInventory::getInstance()->add(new Item(new ItemIdentifier(self::ITEM_GRIND_STONE, 0), 'Login Bonus'));
        StringToItemParser::getInstance()->register('grindstone', fn(string $input) => new Item(new ItemIdentifier(self::ITEM_GRIND_STONE, 0), 'Login Bonus'));
        ItemFactory::getInstance()->register(new Item(new ItemIdentifier(self::ITEM_CHISELED_NETHER_BRICKS, 0), 'MiningToolsRangeCostItem'));
        CreativeInventory::getInstance()->add(new Item(new ItemIdentifier(self::ITEM_CHISELED_NETHER_BRICKS, 0), 'MiningToolsRangeCostItem'));
        StringToItemParser::getInstance()->register('MiningToolsRangeCostItem', fn(string $input) => new Item(new ItemIdentifier(self::ITEM_CHISELED_NETHER_BRICKS, 0), 'MiningToolsRangeCostItem'));
        /*その他*/
        ItemFactory::getInstance()->register(new CookedMutton(new ItemIdentifier(VanillaItems::COOKED_MUTTON()->getId(), 0), '猫用チュール'), true);
        StringToItemParser::getInstance()->register("猫用チュール", fn() => (new CookedMutton(new ItemIdentifier(VanillaItems::COOKED_MUTTON()->getId(), 0), "猫用チュール")));
        ItemFactory::getInstance()->register(new CookedChicken(new ItemIdentifier(VanillaItems::COOKED_CHICKEN()->getId(), 0), '犬用チュール'), true);
        StringToItemParser::getInstance()->register("犬用チュール", fn() => (new CookedChicken(new ItemIdentifier(VanillaItems::COOKED_CHICKEN()->getId(), 0), "犬用チュール")));
        ItemFactory::getInstance()->register(new CookedSalmon(new ItemIdentifier(VanillaItems::COOKED_SALMON()->getId(), 0), 'かき氷'), true);
        StringToItemParser::getInstance()->register("かき氷", fn() => (new CookedSalmon(new ItemIdentifier(VanillaItems::COOKED_SALMON()->getId(), 0), "かき氷")));
        ItemFactory::getInstance()->register(new CookedFish(new ItemIdentifier(VanillaItems::COOKED_FISH()->getId(), 0), 'ラムネ'), true);
        StringToItemParser::getInstance()->register("ラムネ", fn() => (new CookedFish(new ItemIdentifier(VanillaItems::COOKED_FISH()->getId(), 0), "ラムネ")));
        ItemFactory::getInstance()->register(new CookedPorkchop(new ItemIdentifier(VanillaItems::COOKED_PORKCHOP()->getId(), 0), 'アメリカンドック'), true);
        StringToItemParser::getInstance()->register("アメリカンドック", fn() => (new CookedPorkchop(new ItemIdentifier(VanillaItems::COOKED_PORKCHOP()->getId(), 0), "アメリカンドック")));
        ItemFactory::getInstance()->register(new CookedRabbit(new ItemIdentifier(VanillaItems::COOKED_RABBIT()->getId(), 0), 'モンスター'), true);
        StringToItemParser::getInstance()->register("モンスター", fn() => (new CookedRabbit(new ItemIdentifier(VanillaItems::COOKED_RABBIT()->getId(), 0), "モンスター")));
    }

    public static function getInstance(): Main {
        return self::$main;
    }

}