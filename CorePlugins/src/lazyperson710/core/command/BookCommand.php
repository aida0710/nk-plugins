<?php

namespace lazyperson710\core\command;

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
            $sender->sendMessage("Please use in server");
            return;
        }
        $player = $sender;
        $item = $player->getInventory()->getItemInHand();
        $ids = $item->getID();
        $id = "387";
        if ($id !== $ids) {
            $sender->sendMessage("§bBook §7>> §c手に持っているアイテムは本ではありません");
            return;
        }
        $cname = $item->getTitle();
        if ($item->getNamedTag()->getTag('duplicate ') !== null) {
            $sender->sendMessage("§bBook §7>> §c複製された本を更に複製することはできません");
            return;
        } else {
            $nbt = $item->getNamedTag();
            $cname .= "複製本";
            $item->setTitle($cname);
            $nbt->setInt('duplicate', 1);
            $item->setNamedTag($nbt);
        }
        if (!$sender->getInventory()->canAddItem($item)) {
            $sender->sendMessage("§bBook §7>> §cインベントリに空きがないためアイテムを付与することができませんでした");
            return;
        }
        $name = $sender->getName();
        $mymoney = EconomyAPI::getInstance()->myMoney($name);
        $money = 50;
        if ($money > $mymoney) {
            $sender->sendMessage("§bBook §7>> §cお金が足りませんでした");
            return;
        }
        EconomyAPI::getInstance()->reduceMoney($name, $money);
        $item->setCount(1);
        $player->getInventory()->addItem($item);
        $sender->sendMessage("§bBook §7>> §a50円を消費して本を複製しました");
    }

}