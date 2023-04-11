<?php

declare(strict_types = 1);

namespace lazyperson0710\LoginBonus\item;

use pocketmine\item\Item;

readonly class LoginBonusItemInfo {

	public function __construct(
		private Item $item,
		private int $quantity,
		private int $cost,
		private string $customName,
		private array $lore,
		private ?string $formExplanation,
		private array $enchants,
		private array $level,
		private array $nbt) {
	}

	public function getItem() : Item {
		return $this->item;
	}

	public function getQuantity() : int {
		return $this->quantity;
	}

	public function getCost() : int {
		return $this->cost;
	}

	public function getCustomName() : string {
		return $this->customName;
	}

	public function getLore() : array {
		return $this->lore;
	}

	public function getFormExplanation() : ?string {
		return $this->formExplanation;
	}

	public function getEnchants() : array {
		return $this->enchants;
	}

	public function getLevel() : array {
		return $this->level;
	}

	public function getNbt() : array {
		return $this->nbt;
	}

}
