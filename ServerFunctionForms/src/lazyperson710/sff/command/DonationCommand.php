<?php

declare(strict_types = 0);

namespace lazyperson710\sff\command;

use lazyperson710\core\packet\SendForm;
use lazyperson710\sff\form\donation\DonationForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class DonationCommand extends Command {

    public function __construct() {
        parent::__construct('donation', '寄付総額に応じた特典を受け取れます');
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!($sender instanceof Player)) {
            $sender->sendMessage('サーバー内で実行してください');
            return;
        }
        SendForm::Send($sender, (new DonationForm()));
    }
}
