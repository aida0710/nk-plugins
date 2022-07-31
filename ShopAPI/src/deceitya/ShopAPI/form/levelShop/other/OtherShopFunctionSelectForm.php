<?php

namespace deceitya\ShopAPI\form\levelShop\other;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\ShopAPI\form\element\ShopMainCategoryFormButton;
use deceitya\ShopAPI\form\levelShop\other\InvSell\Confirmation;
use deceitya\ShopAPI\form\levelShop\other\SearchShop\InputItemForm;

class OtherShopFunctionSelectForm extends SimpleForm {

    public function __construct() {
        $this
            ->setTitle("Level Shop")
            ->setText("使用したいコンテンツを選択してください")
            ->addElements(
                new ShopMainCategoryFormButton("Inventory内のアイテムを一括売却\nツールや売却値が0円のアイテムは対象外", new Confirmation()),
                new ShopMainCategoryFormButton("アイテムを検索\n表示されてる名前で検索が可能です(日本語)", new InputItemForm()),
            );
    }
}