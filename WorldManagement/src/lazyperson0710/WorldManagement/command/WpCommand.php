<?php

declare(strict_types = 0);

namespace lazyperson0710\WorldManagement\command;

use lazyperson0710\WorldManagement\form\WarpForm;
use lazyperson710\core\packet\SendForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class WpCommand extends Command {

    public function __construct() {
        parent::__construct('wp', 'ワープフォームを開きます');
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) : void {
        if (!($sender instanceof Player)) {
            $sender->sendMessage('サーバー内で実行してください');
            return;
        }
        SendForm::Send($sender, (new WarpForm($sender)));
    }
}
