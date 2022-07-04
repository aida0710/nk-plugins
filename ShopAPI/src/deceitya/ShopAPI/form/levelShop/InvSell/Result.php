<?php

namespace deceitya\ShopAPI\form\levelShop\InvSell;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\form\SimpleForm;
use deceitya\ShopAPI\database\LevelShopAPI;
use onebone\economyapi\EconomyAPI;
use pocketmine\item\ItemIds;
use pocketmine\player\Player;

class Result extends SimpleForm {

    public function __construct(Player $player, int $allCount, int $allSellMoney, string $allItem, int $insufficientLevelAllCount, string $insufficientLevelAllItem) {
        $this
            ->setTitle("Level Shop")
            ->setText("下記のアイテムを売却しても本当によろしいでしょうか？\n\n売却合計金額 {$allSellMoney}円\n売却アイテム合計数 {$allCount}個\n\n売却アイテム一覧\n{$allItem}\nレベル不足で売却ができないアイテム合計数 {$insufficientLevelAllCount}個\nレベル不足で売却ができないアイテム一覧\n{$insufficientLevelAllItem}")
            ->addElements(new Button("はい"));
    }

    public function handleSubmit(Player $player): void {
        $inventory = $player->getInventory();
        $allCount = 0;
        $allSellMoney = 0;
        for ($i = 0, $size = $inventory->getSize(); $i < $size; ++$i) {
            $item = clone $inventory->getItem($i);
            if ($item->getId() == ItemIds::AIR) continue;
            if (LevelShopAPI::getInstance()->getSell($item->getId(), $item->getMeta()) == 0) continue;
            if (LevelShopAPI::getInstance()->checkLevel($player, $item->getId(), $item->getMeta()) === "failure") {
                continue;
            } elseif (LevelShopAPI::getInstance()->checkLevel($player, $item->getId(), $item->getMeta()) === "exception") continue;
            $sellMoney = LevelShopAPI::getInstance()->getSell($item->getId(), $item->getMeta());
            $count = $item->getCount();
            $sellMoney = $sellMoney * $count;
            $allCount += $item->getCount();
            $allSellMoney += $sellMoney;
            $inventory->removeItem($item);
        }
        EconomyAPI::getInstance()->addMoney($player->getName(), $allSellMoney);
        $player->sendMessage("§bLevelShop §7>> §a{$allSellMoney}円で{$allCount}個のアイテムが売却されました");
    }
}
