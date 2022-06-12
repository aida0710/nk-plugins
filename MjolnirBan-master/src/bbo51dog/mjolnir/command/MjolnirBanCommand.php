<?php

namespace bbo51dog\mjolnir\command;

use bbo51dog\mjolnir\model\BanType;
use bbo51dog\mjolnir\service\BanService;
use bbo51dog\mjolnir\Setting;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;

class MjolnirBanCommand extends Command {

    public function __construct() {
        parent::__construct("mjolnirban", "MjolnirBan command", "/mjolnirban [type: player_name | cid | xuid] [literal] [reason]", ["mban"]);
        $this->setPermission("mjolnir.command.mjolnirban");
    }

    /**
     * @inheritDoc
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!isset($args[0]) || !isset($args[1])) {
            $sender->sendMessage($this->getUsage());
            return;
        }
        $reason = empty($args[2]) ? Setting::getInstance()->getDefaultBanReason() : $args[1];
        switch ($args[0]) {
            case BanType::PLAYER_NAME()->name():
                BanService::banName($args[1], $reason);
                Server::getInstance()->getPlayerExact($args[1])?->kick(Setting::getInstance()->getKickMessage());
                break;
            case BanType::CID()->name():
                BanService::banCid($args[1], $reason);
                break;
            case BanType::XUID()->name():
                $player = Server::getInstance()->getPlayerExact($args[1]);
                if (is_null($player)) return;
                BanService::banXuid($player->getXuid(), $reason);
                $player->kick(Setting::getInstance()->getKickMessage());
                break;
            default:
                $sender->sendMessage("Types: " . implode(", ", BanType::getAll()));
                return;
        }
        $sender->sendMessage("You banned $args[1] (Type: $args[0])");
    }
}