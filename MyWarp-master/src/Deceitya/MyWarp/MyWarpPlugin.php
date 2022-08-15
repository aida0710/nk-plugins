<?php

namespace Deceitya\MyWarp;

use Deceitya\MyWarp\Form\MainForm;
use lazyperson710\core\packet\SendForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class MyWarpPlugin extends PluginBase {

    public static Config $cost;
    public static Config $count;

    public function onEnable(): void {
        self::$cost = new Config($this->getDataFolder() . 'cost.yml');
        self::$count = new Config($this->getDataFolder() . 'count.yml');
        Database::getInstance()->open($this);
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        if ($sender instanceof Player) {
            SendForm::Send($sender, (new MainForm()));
        } else {
            $sender->sendMessage('§bMyWarp §7>> §c鯖内でしかできません');
        }
        return true;
    }
}
