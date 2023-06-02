<?php

declare(strict_types = 1);

namespace lazyperson0710\shop\form\item_shop\other;

use bbo51dog\bboform\form\SimpleForm;
use lazyperson0710\shop\form\item_shop\element\FirstBackFormButton;
use lazyperson0710\shop\form\item_shop\element\SendMenuFormButton;
use lazyperson0710\shop\form\item_shop\future\RestrictionShop;

class OtherShopSelectForm extends SimpleForm {

    public function __construct() {
        $this
            ->setTitle('Item Shop')
            ->setText('コンテンツを選択してください')
            ->addElements(
                new SendMenuFormButton("Inventory内のアイテムを一括売却 [ID: invsell]\nツールや売却値が0円のアイテムは対象外", new InvSellConfirmationForm(), RestrictionShop::RESTRICTION_LEVEL_OTHER_SHOP),
                new SendMenuFormButton("アイテムを検索 [ID: search]\n表示されてる名前で検索が可能です(日本語)", new SearchItemForm(), RestrictionShop::RESTRICTION_LEVEL_OTHER_SHOP),
                new FirstBackFormButton('ショップ選択メニューに戻る'),
            );
	}
}
