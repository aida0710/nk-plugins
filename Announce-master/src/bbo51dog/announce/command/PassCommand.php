<?php

namespace bbo51dog\announce\command;

use bbo51dog\announce\form\PassForm;
use bbo51dog\announce\MessageFormat;
use bbo51dog\announce\service\AnnounceService;
use lazyperson0710\WorldManagement\form\WarpForm;
use lazyperson710\core\packet\SendForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class PassCommand extends Command {

    public function __construct() {
        parent::__construct("pass", "パスワードを入力してコマンドを実行可能にする");
        $this->setPermission("announce.command.pass");
    }

    /**
     * @inheritDoc
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!$sender instanceof Player) {
            $sender->sendMessage(TextFormat::RED . "ゲーム内で実行してください");
            return;
        }
        if (AnnounceService::isConfirmed($sender->getName())) {
            $sender->sendMessage(MessageFormat::PREFIX_PASS . TextFormat::RED . "既にアクティベートされています");
            SendForm::Send($sender, (new WarpForm($sender)));
            return;
        }
        SendForm::Send($sender, (new PassForm()));
    }
}