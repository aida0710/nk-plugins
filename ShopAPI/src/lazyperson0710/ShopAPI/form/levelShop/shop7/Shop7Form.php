<?php

declare(strict_types=1);

namespace lazyperson0710\ShopAPI\form\levelShop\shop7;

use bbo51dog\bboform\form\SimpleForm;
use lazyperson0710\ShopAPI\form\element\FirstBackFormButton;
use lazyperson0710\ShopAPI\form\element\ShopItemFormButton;
use lazyperson0710\ShopAPI\form\levelShop\MainLevelShopForm;
use lazyperson710\core\packet\SendForm;
use lazyperson710\core\packet\SoundPacket;
use pocketmine\player\Player;

class Shop7Form extends SimpleForm {

	public function __construct() {
		$contents = [
			"その他ブロック" => "OtherBlocks7",
			"レッドストーン類" => "RedStone",
		];
		$this
			->setTitle("Level Shop")
			->setText("§7選択してください");
		foreach ($contents as $key => $value) {
			$class = __NAMESPACE__ . "\\" . $value;
			$this->addElements(new ShopItemFormButton($key, $class));
		}
		$this->addElements(new FirstBackFormButton("ホームに戻る"));
	}

	public function handleClosed(Player $player) : void {
		SoundPacket::Send($player, 'mob.shulker.close');
		SendForm::Send($player, (new MainLevelShopForm($player)));
	}
}
