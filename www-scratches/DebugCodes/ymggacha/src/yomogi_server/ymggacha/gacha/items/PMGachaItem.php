<?php

declare(strict_types = 1);

namespace ymggacha\src\yomogi_server\ymggacha\gacha\items;

use pocketmine\item\Item;
use pocketmine\player\Player;
use rarkhopper\gacha\IGachaItem;
use rarkhopper\gacha\IRarity;
use rarkhopper\gacha\Rarity;

/**
 * {@see Item}排出用景品クラス
 */
class PMGachaItem implements IGachaItem {

	private Rarity $rarity;
	private Item $item;

	public function __construct(Rarity $rarity, Item $item) {
		$this->rarity = $rarity;
		$this->item = $item;
	}

	public function getRarity() : IRarity {
		return $this->rarity;
	}

	public function getItem() : Item {
		return $this->item;
	}

	public function giveItem(Player $player) : bool {
		$inventory = $player->getInventory();
		if (!$inventory->canAddItem($this->item)) return false;
		$inventory->addItem($this->item);
		return true;
	}

	public function getPopMessage() : string {
		return '[' . $this->getRarity()->getName() . '] ' . $this->item->getName();
	}
}
