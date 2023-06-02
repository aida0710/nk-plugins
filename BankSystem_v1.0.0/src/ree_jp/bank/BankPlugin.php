<?php

declare(strict_types = 0);

namespace ree_jp\bank;

use pocketmine\plugin\PluginBase;
use ree_jp\bank\command\BankCommand;

class BankPlugin extends PluginBase {

    /** @var BankPlugin */
    private static $instance;

    static function getInstance() : BankPlugin {
        return self::$instance;
    }

    public function onEnable() : void {
        $this->getServer()->getCommandMap()->register('bank', new BankCommand());
        parent::onEnable();
    }

    public function onLoad() : void {
        self::$instance = $this;
        parent::onLoad();
    }
}
