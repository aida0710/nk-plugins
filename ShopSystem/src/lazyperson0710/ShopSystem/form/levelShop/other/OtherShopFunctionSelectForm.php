<?php

declare(strict_types = 0);

namespace lazyperson0710\ShopSystem\form\levelShop\other;

use bbo51dog\bboform\form\SimpleForm;
use lazyperson0710\ShopSystem\form\levelShop\element\FirstBackFormButton;
use lazyperson0710\ShopSystem\form\levelShop\element\SendMenuFormButton;
use lazyperson0710\ShopSystem\form\levelShop\future\ShopText;
use lazyperson0710\ShopSystem\form\levelShop\other\InvSell\Confirmation;
use lazyperson0710\ShopSystem\form\levelShop\other\SearchShop\InputItemForm;

class OtherShopFunctionSelectForm extends SimpleForm implements ShopText {

	public function __construct() {
		$this
			->setTitle(self::TITLE)
			->setText(self::CONTENT)
			->addElements(
				new SendMenuFormButton("Inventory内のアイテムを一括売却\nツールや売却値が0円のアイテムは対象外", new Confirmation()),
				new SendMenuFormButton("アイテムを検索\n表示されてる名前で検索が可能です(日本語)", new InputItemForm()),
				new FirstBackFormButton('ショップ選択メニューに戻る'),
			);
	}
}
