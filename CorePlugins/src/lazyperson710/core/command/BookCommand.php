<?php

namespace lazyperson710\core\command;

use lazyperson710\core\packet\SendMessage\SendMessage;
use onebone\economyapi\EconomyAPI;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class BookCommand extends Command {

    public function __construct() {
        parent::__construct("book", "50円で署名済みの本を一冊複製する");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!($sender instanceof Player)) {
            $sender->sendMessage("サーバー内で実行してください");
            return;
        }
        $player = $sender;
        $item = $player->getInventory()->getItemInHand();
        $ids = $item->getID();
        if (387 !== $ids) {
            SendMessage::Send($player, "手に持っているアイテムは署名済みの本ではありません", "Book", false);
            return;
        }
        $cname = $item->getTitle();
        if ($item->getNamedTag()->getTag('duplicate') !== null) {
            SendMessage::Send($sender, "複製された本を更に複製することはできません", "Book", false);
            return;
        } else {
            $nbt = $item->getNamedTag();
            $cname .= "複製本";
            $item->setTitle($cname);
            $nbt->setInt('duplicate', 1);
            $item->setNamedTag($nbt);
        }
        if (!$sender->getInventory()->canAddItem($item)) {
            SendMessage::Send($sender, "インベントリに空きがないためアイテムを付与することができませんでした", "Book", false);
            return;
        }
        $name = $sender->getName();
        $mymoney = EconomyAPI::getInstance()->myMoney($name);
        $money = 50;
        if ($money > $mymoney) {
            SendMessage::Send($sender, "お金が足りませんでした", "Book", false);
            return;
        }
        EconomyAPI::getInstance()->reduceMoney($name, $money);
        $item->setCount(1);
        $player->getInventory()->addItem($item);
        SendMessage::Send($sender, "50円を消費して本を複製しました", "Book", true);
    }

}