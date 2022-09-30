<?php

namespace lazyperson0710\LoginBonus;

use lazyperson0710\LoginBonus\command\LoginBonusCommand;
use lazyperson0710\LoginBonus\event\JoinPlayerEvent;
use lazyperson0710\LoginBonus\event\LoginBonusItemTap;
use lazyperson0710\LoginBonus\item\ItemRegister;
use lazyperson0710\LoginBonus\task\CheckChangeDateTask;
use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;

class Main extends PluginBase {

    use SingletonTrait;

    public Config $lastBonusDateConfig;
    public Item $loginBonusItem;
    public const LoginBonusItemInfo = [
        "id" => -195,
        "meta" => 0,
        "count" => 1,
        "enchant" => 17,
        "level" => 15,
    ];

    public function onLoad(): void {
        self::setInstance($this);
    }

    public function onEnable(): void {
        $this->getServer()->getCommandMap()->registerAll("LoginBonus", [
            new LoginBonusCommand(),
        ]);
        ItemRegister::getInstance()->init();
        date_default_timezone_set('Asia/Tokyo');
        $this->reloadConfig();
        Main::getInstance()->lastBonusDateConfig = new Config($this->getDataFolder() . "lastBonus.yml", Config::YAML);
        $this->getScheduler()->scheduleRepeatingTask(new CheckChangeDateTask($this), 20 * 60);
        $this->getServer()->getPluginManager()->registerEvents(new JoinPlayerEvent(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new LoginBonusItemTap(), $this);
        $this->loginBonusItem = $this->registerLoginBonusItem();
    }

    private function registerLoginBonusItem(): Item {
        $item = ItemFactory::getInstance()->get((int)self::LoginBonusItemInfo["id"], (int)Main::LoginBonusItemInfo["meta"], (int)Main::LoginBonusItemInfo["count"]);
        $item->addEnchantment(new EnchantmentInstance(EnchantmentIdMap::getInstance()->fromId((int)Main::LoginBonusItemInfo["enchant"]), (int)Main::LoginBonusItemInfo["level"]));
        $item->setCustomName("Login Bonus");
        $item->setLore(["アイテムを持ってタップすることでログインボーナスとアイテムを交換することができます",]);
        return $item;
    }
}