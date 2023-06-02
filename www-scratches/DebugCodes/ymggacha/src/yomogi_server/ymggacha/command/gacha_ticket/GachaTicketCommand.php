<?php

declare(strict_types = 1);

namespace ymggacha\src\yomogi_server\ymggacha\command\gacha_ticket;

use CortexPE\Commando\args\IntegerArgument;
use CortexPE\Commando\BaseCommand;
use CortexPE\Commando\exception\ArgumentOrderException;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use ymggacha\src\yomogi_server\ymggacha\command\CommandMessages;
use ymggacha\src\yomogi_server\ymggacha\command\CommandPermissions;
use ymggacha\src\yomogi_server\ymggacha\item\YomogiGachaTicket;
use function is_numeric;
use function str_replace;

class GachaTicketCommand extends BaseCommand {

    private const NAME = 'gacha_ticket';
    private const DESCRIPTION = 'ガチャチケットを出します (デバッグ用)';
    private const ALIASES = [];
    private const AMOUNT = 'amount';

    public function __construct(PluginBase $plugin) {
        parent::__construct($plugin, self::NAME, self::DESCRIPTION, self::ALIASES);
        $this->setPermission(CommandPermissions::OP);
    }

    /**
     * @param array<string, string> $args
     */
    public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void {
        if (!$sender instanceof Player) {
            $sender->sendMessage(CommandMessages::EXECUTED_NON_PLAYER);
            return;
        }
        if (!Server::getInstance()->isOp($sender->getName())) {
            $sender->sendMessage(CommandMessages::EXECUTED_NOT_OP);
            return;
        }
        $amount = $args[self::AMOUNT] ?? 64;
        if (!is_numeric($amount)) {
            $sender->sendMessage(CommandMessages::INVALID_FORMAT_TICKET_CNT);
            return;
        }
        $ticket = (new YomogiGachaTicket())->init()->setCount((int) $amount);
        $sender->getInventory()->addItem($ticket);
        $sender->sendMessage(str_replace('%amount', (string) $amount, CommandMessages::ADDED_TICKET));
    }

    /**
     * @throws ArgumentOrderException
     */
    protected function prepare() : void {
        $this->registerArgument(0, new IntegerArgument(self::AMOUNT, true));
    }
}
