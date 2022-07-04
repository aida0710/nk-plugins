<?php

namespace lazyperson710\core\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\player\Player;

class MajorCommand extends Command {

    public function __construct() {
        parent::__construct("major", "ブロック間を簡単に計測できるアイテムを付与します");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!($sender instanceof Player)) {
            $sender->sendMessage("Please use in server");
            return;
        }
        $major = ItemFactory::getInstance()->get(ItemIds::FLINT);
        $major->setCustomName("Major");
        $major->setLore([
            "lore1" => "ブロック間を簡単に測定出来ます",
            "lore2" => "一度目のタップで始点を設定し、二度目以降のタップで終点を設定できます",
            "lore3" => "また、スニークしながらタップすることで設定したポイントを削除することが出来ます",
        ]);
        if ($sender->getInventory()->contains($major)) {
            $sender->sendMessage("§bMajor §7>> §c既にMajorがインベントリに存在する為、処理が中断されました");
            return;
        }
        if ($sender->getInventory()->canAddItem($major)) {
            $sender->getInventory()->addItem($major);
            $sender->sendMessage("§bMajor §7>> §aMajorを一つ配布しました。使いかたはアイテム説明をご覧ください");
        } else {
            $sender->sendMessage("§bMajor §7>> §cインベントリが満タンの為、majorを配布できませんでした");
        }
    }

}