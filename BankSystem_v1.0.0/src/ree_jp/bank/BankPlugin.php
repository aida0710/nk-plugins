<?php

namespace ree_jp\bank;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use ree_jp\bank\command\BankCommand;
use ree_jp\bank\event\EventListener;

class BankPlugin extends PluginBase {

    /**
     * @var BankPlugin
     */
    private static $instance;

    static function getInstance(): BankPlugin {
        return self::$instance;
    }

    function onLoad(): void {
        self::$instance = $this;
        parent::onLoad();
    }

    function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
        $this->getServer()->getCommandMap()->register("bank", new BankCommand());
        parent::onEnable();
    }
}