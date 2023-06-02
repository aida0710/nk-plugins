<?php

declare(strict_types = 0);

namespace lazyperson0710\PlayerSetting\command;

use lazyperson0710\PlayerSetting\form\SelectSettingForm;
use lazyperson710\core\packet\SendForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class SettingCommand extends Command {

    public function __construct() {
        parent::__construct('settings', 'サーバーの個人用設定画面を開く');
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) : void {
        if (!($sender instanceof Player)) {
            $sender->sendMessage('サーバー内で実行してください');
            return;
        }
        SendForm::Send($sender, (new SelectSettingForm($sender)));
    }
}
