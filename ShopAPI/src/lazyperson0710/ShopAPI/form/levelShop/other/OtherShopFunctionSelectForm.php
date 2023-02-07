<?php

declare(strict_types=1);

namespace lazyperson0710\ShopAPI\form\levelShop\other;

use bbo51dog\bboform\form\SimpleForm;
use lazyperson0710\ShopAPI\form\element\FirstBackFormButton;
use lazyperson0710\ShopAPI\form\element\ShopMainCategoryFormButton;
use lazyperson0710\ShopAPI\form\levelShop\MainLevelShopForm;
use lazyperson0710\ShopAPI\form\levelShop\other\InvSell\Confirmation;
use lazyperson0710\ShopAPI\form\levelShop\other\SearchShop\InputItemForm;
use lazyperson710\core\packet\SendForm;
use lazyperson710\core\packet\SoundPacket;
use pocketmine\player\Player;

class OtherShopFunctionSelectForm extends SimpleForm {

	public function __construct() {
		$this
			->setTitle("Level Shop")
			->setText("使用したいコンテンツを選択してください")
			->addElements(
				new ShopMainCategoryFormButton("Inventory内のアイテムを一括売却\nツールや売却値が0円のアイテムは対象外", new Confirmation()),
				new ShopMainCategoryFormButton("アイテムを検索\n表示されてる名前で検索が可能です(日本語)", new InputItemForm()),
				new FirstBackFormButton("ホームに戻る"),
			);
	}

	public function handleClosed(Player $player) : void {
		SoundPacket::Send($player, 'mob.shulker.close');
		SendForm::Send($player, (new MainLevelShopForm($player)));
	}
}
