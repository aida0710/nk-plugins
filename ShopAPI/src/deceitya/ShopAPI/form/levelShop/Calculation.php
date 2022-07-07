<?php

namespace deceitya\ShopAPI\form\levelShop;

use deceitya\ShopAPI\database\LevelShopAPI;
use deceitya\ShopAPI\form\element\SecondBackFormButton;
use deceitya\ShopAPI\form\element\SellBuyItemFormButton;
use pocketmine\item\Item;

class Calculation {

    public function sendButton(string $shopNumber, array $items, $class): void {
        $api = LevelShopAPI::getInstance();
        $class
            ->setTitle("Level Shop")
            ->setText("§7選択してください");
        foreach ($items as $item) {
            $id = $item;
            $meta = 0;
            if ($item instanceof Item) {
                $id = $item->getId();
                $meta = $item->getMeta();
            }
            $class->addElements(new SellBuyItemFormButton("{$api->getItemName($id ,$meta)}\n購入:{$api->getBuy($id ,$meta)} / 売却:{$api->getSell($id ,$meta)}", $id, $meta));
        }
        $shopNumber = str_replace("shop", "", $shopNumber);
        $shopNumber = (int)$shopNumber;
        $class->addElements(new SecondBackFormButton("一つ戻る", $shopNumber));
    }
}