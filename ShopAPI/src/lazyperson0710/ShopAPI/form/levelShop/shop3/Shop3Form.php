<?php

declare(strict_types = 0);

namespace lazyperson0710\ShopSystem\form\levelShop\shop3;

use bbo51dog\bboform\form\SimpleForm;
use lazyperson0710\ShopSystem\form\element\FirstBackFormButton;
use lazyperson0710\ShopSystem\form\element\ShopItemFormButton;
use lazyperson0710\ShopSystem\form\levelShop\MainLevelShopForm;
use lazyperson710\core\packet\SendForm;
use lazyperson710\core\packet\SoundPacket;
use pocketmine\player\Player;

class Shop3Form extends SimpleForm {

	public function __construct() {
		$contents = [
			'建材ブロック類' => 'BuildingMaterials',
			'その他ブロック' => 'OtherBlocks3',
			'原石ブロック類' => 'Ores',
			'染料アイテム' => 'Dyes',
		];
		$this
			->setTitle('Level Shop')
			->setText('§7選択してください');
		foreach ($contents as $key => $value) {
			$class = __NAMESPACE__ . "\\" . $value;
			$this->addElements(new ShopItemFormButton($key, $class));
		}
		$this->addElements(new FirstBackFormButton('ホームに戻る'));
	}

	public function handleClosed(Player $player) : void {
		SoundPacket::Send($player, 'mob.shulker.close');
		SendForm::Send($player, (new MainLevelShopForm($player)));
	}
}
