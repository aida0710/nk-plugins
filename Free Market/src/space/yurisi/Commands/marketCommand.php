<?php

declare(strict_types=1);
namespace space\yurisi\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\CommandException;
use pocketmine\player\Player;
use space\yurisi\Form\MainForm;

class marketCommand extends Command {

    public function __construct() {
        parent::__construct("market", "フリーマーケットを開く", "/market");
    }

    /**
     * @param string[] $args
     *
     * @return bool
     * @throws CommandException
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if ($sender instanceof Player) {
            $sender->sendForm(new MainForm());
        }
    }
}