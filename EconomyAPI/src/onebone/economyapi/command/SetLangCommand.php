<?php

declare(strict_types = 0);

namespace onebone\economyapi\command;

use onebone\economyapi\EconomyAPI;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use function array_shift;
use function trim;

class SetLangCommand extends Command {

    public function __construct(private EconomyAPI $plugin) {
        $desc = $plugin->getCommandMessage('setlang');
        parent::__construct('setlang', $desc['description'], $desc['usage']);
        $this->setPermission('economyapi.command.setlang');
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, string $label, array $params) : bool {
        if (!$this->plugin->isEnabled()) return false;
        if (!$this->testPermission($sender)) {
            return false;
        }
        $lang = array_shift($params);
        if (trim($lang) === '') {
            $sender->sendMessage(TextFormat::RED . 'Usage: ' . $this->getUsage());
            return true;
        }
        if ($this->plugin->setPlayerLanguage($sender->getName(), $lang)) {
            $sender->sendMessage($this->plugin->getMessage('language-set', [$lang], $sender->getName()));
        } else {
            $sender->sendMessage(TextFormat::RED . "There is no language such as $lang");
        }
        return true;
    }
}
