<?php

namespace deceitya\ShopAPI\form\levelShop\other;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\ShopAPI\form\element\ShopMainCategoryFormButton;
use deceitya\ShopAPI\form\levelShop\other\InvSell\Confirmation;
use pocketmine\player\Player;

class OtherShopFunctionSelectForm extends SimpleForm {

    public function __construct(Player $player, ?string $error = null) {
        $this
            ->setTitle("")
            ->setText("見たいコンテンツを選択してください\n{$error}")
            ->addElements(
                new ShopMainCategoryFormButton("Inventory内のアイテムを一括売却\nツールや売却値が0円のアイテムは対象外", new Confirmation())
            );
    }
}