<?php

namespace ree_jp\bank\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\types\command\CommandEnum;
use pocketmine\network\mcpe\protocol\types\command\CommandParameter;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use ree_jp\bank\form\BankForm;

class BankCommand extends Command {

    function __construct(string $name = "bank", string $description = "銀行システム", string $usageMessage = null, array $aliases = []) {
        $overloads = [
            [
                CommandParameter::enum("bankMode",new CommandEnum("bankMode", ["ranking", "create", "delete", "log", "put", "out", "share", "transfer"]), AvailableCommandsPacket::ARG_TYPE_STRING, true),
                CommandParameter::standard("bank", AvailableCommandsPacket::ARG_TYPE_STRING),
                CommandParameter::standard("money", AvailableCommandsPacket::ARG_TYPE_INT),
            ],
        ];
        parent::__construct($name, $description, $usageMessage, $aliases, $overloads);
        $this->setPermission("command.banksystem.true");
    }

    /**
     * @inheritDoc
     */
    function execute(CommandSender $sender, string $commandLabel, array $args) {
        if ($sender instanceof Player) {
            if ($this->testPermission($sender)) {
                // TODO
                $sender->sendForm(new BankForm());
            }
        } else $sender->sendMessage(TextFormat::RED . "§bBank §7>> §cコマンドを実行出来ませんでした");
        return true;
    }
}