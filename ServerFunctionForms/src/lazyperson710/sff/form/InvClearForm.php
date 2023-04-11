<?php

declare(strict_types = 0);

namespace lazyperson710\sff\form;

use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\element\Toggle;
use bbo51dog\bboform\form\CustomForm;
use lazyperson710\core\packet\SendMessage\SendMessage;
use pocketmine\item\ItemIds;
use pocketmine\player\Player;

class InvClearForm extends CustomForm {

	private Toggle $Armor;
	private Toggle $Hotbar;

	public function __construct() {
		$this->Armor = new Toggle('Armorインベントリの削除(default/off)', false);
		$this->Hotbar = new Toggle('ホットバーアイテムの削除(default/off)', false);
		$this
			->setTitle('InventoryClear')
			->addElements(
				new label('インベントリ内のアイテムを削除します'),
				$this->Armor,
				$this->Hotbar,
			);
	}

	public function handleSubmit(Player $player) : void {
		$inventory = $player->getInventory();
		$count = 0;
		$armorCount = 0;
		for ($i = 0, $size = $inventory->getSize(); $i < $size; ++$i) {
			$item = clone $inventory->getItem($i);
			if ($item->getId() == ItemIds::AIR) continue;
			if ($this->Hotbar->getValue() !== true) {
				if ($i >= 0 && $i <= $inventory->getHotbarSize() - 1) continue;
			}
			$inventory->clear($i);
			$count += $item->getCount();
		}
		if ($this->Armor->getValue() === true) {
			$armorInventory = $player->getArmorInventory();
			for ($i = 0, $size = $armorInventory->getSize(); $i < $size; ++$i) {
				$item = clone $armorInventory->getItem($i);
				if ($item->getId() == ItemIds::AIR) continue;
				$armorInventory->clear($i);
				$armorCount += $item->getCount();
			}
			$armorInventory->getContents();
		}
		if (($count + $armorCount) == 0) {
			SendMessage::Send($player, 'インベントリからは何も消去されませんでした', 'InvClear', false);
			return;
		}
		if (($armorCount) == 0) {
			SendMessage::Send($player, "インベントリから{$count}個のアイテムが削除されました", 'InvClear', true);
			return;
		}
		if (($count) == 0) {
			SendMessage::Send($player, "Armorインベントリから{$armorCount}個のアイテムが削除されました", 'InvClear', true);
			return;
		}
		SendMessage::Send($player, "インベントリから{$count}個、Armorインベントリからは{$armorCount}個のアイテムが削除されました", 'InvClear', true);
	}
}
