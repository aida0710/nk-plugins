<?php

declare(strict_types = 1);

namespace lazyperson0710\ShopSystem\form\levelShop;

use bbo51dog\bboform\form\SimpleForm;
use lazyperson0710\ShopSystem\database\ItemShopAPI;
use lazyperson0710\ShopSystem\form\levelShop\element\FirstBackFormButton;
use lazyperson0710\ShopSystem\form\levelShop\element\ShopCategoryButton;
use lazyperson0710\ShopSystem\form\levelShop\future\ShopCategory;
use lazyperson0710\ShopSystem\form\levelShop\future\ShopText;

class CategorySelectForm extends SimpleForm implements ShopText {

	public function __construct(int $shopNumber) {
		$shopCategory = array_keys(ItemShopAPI::getInstance()->getCategory($shopNumber));
		$this
			->setTitle(self::TITLE)
			->setText(self::CONTENT);
		foreach ($shopCategory as $category) {
			$displayName = ShopCategory::getInstance()->getCategoryByDisplayName($category);
			$this->addElement(new ShopCategoryButton($displayName, $shopNumber, $category));
		}
		$this->addElement(new FirstBackFormButton('ショップ選択メニューに戻る'));
	}

}