<?php

declare(strict_types = 0);
namespace lazyperson0710\ShopAPI\form\levelShop\other\SearchShop;

use bbo51dog\bboform\element\Input;
use bbo51dog\bboform\form\CustomForm;
use lazyperson0710\ShopAPI\database\LevelShopAPI;
use lazyperson710\core\packet\SendForm;
use lazyperson710\core\packet\SoundPacket;
use pocketmine\player\Player;
use function preg_match;
use function str_contains;

class InputItemForm extends CustomForm {

	private Input $itemName;

	public function __construct(?string $msg = null) {
		$this->itemName = new Input("{$msg}調べたいアイテムの名前を入力してください\n入力した値が含まれている名前のアイテムがすべて表示されます", '石');
		$this
			->setTitle('Level Shop')
			->addElements(
				$this->itemName,
			);
	}

	public function handleSubmit(Player $player) : void {
		$items = [];
		if (!preg_match('/[ぁ-ん]+|[ァ-ヴー]+|[一-龠]/u', $this->itemName->getValue())) {
			SendForm::Send($player, (new InputItemForm("§c例外が発生しました\nitemNameの入力欄には日本語(ひらがな/カタカナ/漢字)を含める必要があります\n")));
			SoundPacket::Send($player, 'dig.chain');
			return;
		}
		foreach (LevelShopAPI::getInstance()->getItemNameVariable() as $itemArray) {
			foreach ($itemArray as $itemName) {
				if (str_contains($itemName, $this->itemName->getValue())) {
					$items[] = LevelShopAPI::getInstance()->getDataFromItemName($itemName);
				}
			}
		}
		SendForm::Send($player, (new SearchResultForm($player, $items)));
	}
}
