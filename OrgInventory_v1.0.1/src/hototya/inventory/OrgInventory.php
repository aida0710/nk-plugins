<?php

namespace hototya\inventory;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;

class OrgInventory extends PluginBase {

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        if ($label === "inv") {
            if ($sender instanceof Player) {
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
        return true;
    }
}