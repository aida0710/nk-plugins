<?php

namespace deceitya\ShopAPI\form\levelShop;

use Deceitya\MiningLevel\MiningLevelAPI;
use deceitya\ShopAPI\database\LevelShopAPI;
use deceitya\ShopAPI\form\element\SecondBackFormButton;
use deceitya\ShopAPI\form\element\SellBuyItemFormButton;
use deceitya\ShopAPI\form\element\ShopMainCategoryFormButton;
use deceitya\ShopAPI\form\levelShop\other\SearchShop\InputItemForm;
use pocketmine\item\Item;
use pocketmine\player\Player;

class Calculation {

    public function sendButton(Player $player, string $shopNumber, array $items, $class): void {
        $api = LevelShopAPI::getInstance();
        if (empty($items)) {
            $text = "§c検索した値ではアイテムは検出されませんでした";
        } else $text = "§7選択してください";
        $class
            ->setTitle("Level Shop")
            ->setText($text);
        foreach ($items as $item) {
            $id = $item;
            $meta = 0;
            if ($item instanceof Item) {
                $id = $item->getId();
                $meta = $item->getMeta();
            }
            if (MiningLevelAPI::getInstance()->getLevel($player) < LevelShopAPI::getInstance()->getLevel($id, $meta)) {
                $error = "§c{$api->getItemName($id ,$meta)} - レベル不足/{$api->getLevel($id, $meta)}§r";
            } else $error = "{$api->getItemName($id ,$meta)}";
            $class->addElements(new SellBuyItemFormButton("{$error}\n購入:{$api->getBuy($id ,$meta)} / 売却:{$api->getSell($id ,$meta)}", $id, $meta));
        }
        if ($shopNumber === "search") {
            $class->addElements(new ShopMainCategoryFormButton("検索画面に戻る", new InputItemForm()));
            return;
        }
        $shopNumber = str_replace("shop", "", $shopNumber);
        $shopNumber = (int)$shopNumber;
        $class->addElements(new SecondBackFormButton("一つ戻る", $shopNumber));
    }
}