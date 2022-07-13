<?php

declare(strict_types = 1);
namespace nkserver\ranking\command\sub;

use nkserver\ranking\form\SearchPlayerForm;
use nkserver\ranking\libs\CortexPE\Commando\args\RawStringArgument;
use nkserver\ranking\libs\CortexPE\Commando\BaseSubCommand;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class SearchSubCommand extends BaseSubCommand {

    public function __construct(Plugin $plugin) {
        parent::__construct(
            $plugin,
            'search',
            '他のプレイヤーの統計を確認できます',
        );
    }

    public function onRun(CommandSender $sender, string $command, array $args): void {
        if (!$sender instanceof Player) {
            $sender->sendMessage(TextFormat::RED . 'ゲーム内で実行してください');
            return;
        }
        if (!Server::getInstance()->isOp((string)$sender)) return;
        $sender->sendForm(
            isset($args['target']) ?
                SearchPlayerForm::getStatistics((string)$args['target']) :
                new SearchPlayerForm
        );
    }

    protected function prepare(): void {
        $this->setPermission('conquest.command.op');
        $this->registerArgument(0, new RawStringArgument('target', true));
    }
}