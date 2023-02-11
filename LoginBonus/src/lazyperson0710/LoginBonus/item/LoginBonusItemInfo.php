<?php

declare(strict_types = 0);
namespace lazyperson0710\LoginBonus\item;

use pocketmine\item\Item;

class LoginBonusItemInfo {

	private Item $item;
	private int $quantity;
	private int $cost;
	private string $customName;
	private array $lore;
	private ?string $formExplanation;
	private array $enchants;
	private array $level;
	private array $nbt;

	public function __construct(Item $item, int $quantity, int $cost, string $customName, array $lore, ?string $formExplanation, array $enchants, array $level, array $nbt) {
		$this->item = $item;
		$this->quantity = $quantity;
		$this->cost = $cost;
		$this->customName = $customName;
		$this->lore = $lore;
		$this->formExplanation = $formExplanation;
		$this->enchants = $enchants;
		$this->level = $level;
		$this->nbt = $nbt;
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
