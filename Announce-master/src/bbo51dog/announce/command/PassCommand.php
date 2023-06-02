<?php

declare(strict_types = 0);

namespace bbo51dog\announce\command;

use bbo51dog\announce\form\PassForm;
use bbo51dog\announce\service\AnnounceService;
use lazyperson0710\WorldManagement\form\WarpForm;
use lazyperson710\core\packet\SendForm;
use lazyperson710\core\packet\SendMessage\SendMessage;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class PassCommand extends Command {

    public function __construct() {
        parent::__construct('pass', 'パスワードを入力してコマンドを実行可能にする');
        $this->setPermission('announce.command.pass');
    }

    /**
     * @inheritDoc
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!$sender instanceof Player) {
            $sender->sendMessage(TextFormat::RED . 'ゲーム内で実行してください');
            return;
        }
        if (AnnounceService::isConfirmed($sender->getName())) {
            SendMessage::Send($sender, '既にアクティベートされています', 'Pass', false);
            SendForm::Send($sender, (new WarpForm($sender)));
            return;
        }
        SendForm::Send($sender, (new PassForm()));
    }
}
