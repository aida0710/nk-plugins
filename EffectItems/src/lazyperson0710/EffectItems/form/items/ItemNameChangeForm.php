<?php

declare(strict_types = 1);
namespace lazyperson0710\EffectItems\form\items;

use bbo51dog\bboform\element\Input;
use bbo51dog\bboform\form\CustomForm;
use lazyperson710\core\packet\SendMessage\SendMessage;
use pocketmine\item\Durable;
use pocketmine\item\ItemIds;
use pocketmine\player\Player;

class ItemNameChangeForm extends CustomForm {

	private Input $itemName;

	public function __construct() {
		$this->itemName = new Input("所持しているアイテムに付与したい名前を入力してください\n名前を変更できるのは道具のみとなります", 'なまけものソード');
		$this
			->setTitle('Item Edit')
			->addElements(
				$this->itemName,
			);
	}

	public function handleSubmit(Player $player) : void {
		if (!$player->getInventory()->getItemInHand() instanceof Durable) {
			SendMessage::Send($player, '道具や装備以外のアイテムは名前を変更することができません', 'ItemEdit', false);
			return;
		}
		$approval = false;
		for ($i = 0, $size = $player->getInventory()->getSize(); $i < $size; ++$i) {
			$item = clone $player->getInventory()->getItem($i);
			if ($item->getId() == ItemIds::AIR) continue;
			if ($item->getNamedTag()->getTag('ItemNameChangeIngot') !== null) {
				$player->getInventory()->removeItem($item->setCount(1));
				$approval = true;
				break;
			}
		}
		if ($approval === false) {
			SendMessage::Send($player, 'アイテム名変更アイテムを取得することができませんでした', 'ItemEdit', false);
			return;
		}
		$inHandItem = $player->getInventory()->getItemInHand();
		$inHandItem->setCustomName($this->itemName->getValue());
		$player->getInventory()->setItemInHand($inHandItem);
		SendMessage::Send($player, "アイテム名を{$this->itemName->getValue()}§r§aに変更しました", 'ItemEdit', true);
	}
}
