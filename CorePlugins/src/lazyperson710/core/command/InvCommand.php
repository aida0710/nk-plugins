<?php

namespace lazyperson710\core\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class InvCommand extends Command {

    public function __construct() {
        parent::__construct("inv", "プレイヤーインベントリをID順に整理します");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!($sender instanceof Player)) {
            $sender->sendMessage("Please use in server");
            return;
        }
        $inventory = $sender->getInventory();
        $list = [];
        foreach ($inventory->getContents() as $items) {
            $list[$items->getId()][] = $items;
        }
        ksort($list);
        $new = [];
        foreach ($list as $key => $value) {
            foreach ($value as $item) {
                $new[] = $item;
            }
        }
        $inventory->setContents($new);
        $sender->sendMessage("§bInventory §7>> §aインベントリをid順に整理しました");
    }

}