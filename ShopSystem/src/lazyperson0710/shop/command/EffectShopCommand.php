<?php

declare(strict_types = 1);

namespace lazyperson0710\shop\command;

use lazyperson0710\shop\form\effect_shop\EffectSelectForm;
use lazyperson710\core\packet\SendForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class EffectShopCommand extends Command {

    public function __construct() {
        parent::__construct('ef', 'エフェクトショップを開くことができます');
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) : void {
        if (!($sender instanceof Player)) {
            $sender->sendMessage('サーバー内で実行してください');
            return;
        }
        SendForm::Send($sender, (new EffectSelectForm()));
    }
}
