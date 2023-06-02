<?php

declare(strict_types = 0);

namespace lazyperson0710\EffectItems\command;

use lazyperson0710\EffectItems\form\items\SelectContentForm;
use lazyperson710\core\packet\SendForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class ItemsCommand extends Command {

    public function __construct() {
        parent::__construct('items', 'アイテム関係の編集ができます');
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!($sender instanceof Player)) {
            $sender->sendMessage('サーバー内で実行してください');
            return;
        }
        SendForm::Send($sender, (new SelectContentForm($sender)));
    }

}
