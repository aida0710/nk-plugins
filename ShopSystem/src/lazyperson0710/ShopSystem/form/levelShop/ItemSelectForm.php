<?php

declare(strict_types = 1);

namespace lazyperson0710\ShopSystem\form\levelShop;

use bbo51dog\bboform\form\SimpleForm;
use lazyperson0710\ShopSystem\database\ItemShopAPI;
use lazyperson0710\ShopSystem\form\levelShop\element\FirstBackFormButton;
use lazyperson0710\ShopSystem\form\levelShop\future\ShopText;

class ItemSelectForm extends SimpleForm implements ShopText {

	public function __construct(int $shopNumber, string $category) {
		$shopItems = ItemShopAPI::getInstance()->getCategoryItems($shopNumber, $category);
		$this
			->setTitle(self::TITLE)
			->setText(self::CONTENT);
		foreach ($shopItems as $item) {
			//todo 実際にアイテムの確認画面に移行するように修正
			$this->addElement(new ShopItemButton($item->getDisplayName(), $shopNumber, $category));
		}
		$this->addElement(new FirstBackFormButton('ショップ選択メニューに戻る'));
	}

}