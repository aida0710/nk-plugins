<?php

declare(strict_types = 1);
namespace nkserver\ranking\command;

use lazyperson710\core\packet\SendForm;
use nkserver\ranking\command\sub\SearchSubCommand;
use nkserver\ranking\form\HomeForm;
use nkserver\ranking\libs\CortexPE\Commando\BaseCommand;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

class RankingCommand extends BaseCommand {

    public function __construct(PluginBase $owner) {
        parent::__construct(
            $owner,
            'ranking',
            '統計情報・ランキングを確認できます',
        );
    }

    public function onRun(CommandSender $sender, string $command, array $args): void {
        if (!$sender instanceof Player) {
            $sender->sendMessage(TextFormat::RED . 'ゲーム内で実行してください');
            return;
        }
        SendForm::Send($sender, (new HomeForm));
    }

    protected function prepare(): void {
        $this->setPermission('conquest.command.public');
        $this->registerSubCommand(new SearchSubCommand($this->getOwningPlugin()));
    }
}