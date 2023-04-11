<?php

declare(strict_types = 0);

namespace lazyperson0710\ShopSystem\event;

use Error;
use lazyperson0710\ShopSystem\object\LevelShopItem;
use pocketmine\event\Event;
use pocketmine\player\Player;

class LevelShopClosingEvent extends Event {

	public function __construct(
		private Player $player,
		private LevelShopItem $item,
		private string $type,
	) {
		if (($this->type === 'buy' || $this->type === 'sell') === false) {
			throw new Error('不明なタイプが指定されました -> ' . $this->type);
		}
	}

	public function getPlayer() : Player {
		return $this->player;
	}

	public function getItem() : LevelShopItem {
		return $this->item;
	}

	public function getType() : string {
		return $this->type;
	}

}
