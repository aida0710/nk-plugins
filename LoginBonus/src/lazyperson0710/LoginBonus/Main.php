<?php

declare(strict_types = 0);

namespace lazyperson0710\LoginBonus;

use lazyperson0710\LoginBonus\command\LoginBonusCommand;
use lazyperson0710\LoginBonus\event\JoinPlayerEvent;
use lazyperson0710\LoginBonus\item\ItemRegister;
use lazyperson0710\LoginBonus\task\CheckChangeDateTask;
use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;
use function date_default_timezone_set;

class Main extends PluginBase {

    use SingletonTrait;

    public const LoginBonusItemInfo = [
        'id' => -195,
        'meta' => 0,
        'count' => 1,
        'enchant' => 18,
        'level' => 15,
    ];
    public Config $lastBonusDateConfig;
    public Item $loginBonusItem;

    protected function onEnable() : void {
        $this->getServer()->getCommandMap()->registerAll('LoginBonus', [
            new LoginBonusCommand(),
        ]);
        ItemRegister::getInstance()->init();
        date_default_timezone_set('Asia/Tokyo');
        $this->reloadConfig();
        Main::getInstance()->lastBonusDateConfig = new Config($this->getDataFolder() . 'lastBonus.yml', Config::YAML);
        $this->getScheduler()->scheduleRepeatingTask(new CheckChangeDateTask($this), 20 * 60);
        $this->getServer()->getPluginManager()->registerEvents(new JoinPlayerEvent(), $this);
        $this->loginBonusItem = $this->registerLoginBonusItem();
    }

    private function registerLoginBonusItem() : Item {
        $item = ItemFactory::getInstance()->get((int) self::LoginBonusItemInfo['id'], (int) Main::LoginBonusItemInfo['meta'], (int) Main::LoginBonusItemInfo['count']);
        $item->addEnchantment(new EnchantmentInstance(EnchantmentIdMap::getInstance()->fromId((int) Main::LoginBonusItemInfo['enchant']), (int) Main::LoginBonusItemInfo['level']));
        $item->setCustomName('Login Bonus');
        $item->setLore(['アイテムを持ってタップすることでログインボーナスとアイテムを交換することができます',]);
        return $item;
    }

    protected function onLoad() : void {
        self::setInstance($this);
    }
}
