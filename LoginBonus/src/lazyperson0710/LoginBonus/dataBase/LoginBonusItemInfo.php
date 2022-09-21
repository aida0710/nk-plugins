<?php

namespace lazyperson0710\LoginBonus\dataBase;

use pocketmine\item\Item;

class LoginBonusItemInfo {

    private Item $item;
    private int $quantity;
    private int $cost;
    private string $customName;
    private array|null $lore;
    private string|null $formExplanation;

    public function __construct(Item $item, int $quantity, int $cost, string $customName, ?array $lore = null, ?string $formExplanation = null) {
        $this->item = $item;
        $this->quantity = $quantity;
        $this->cost = $cost;
        $this->customName = $customName;
        $this->lore = $lore;
        $this->formExplanation = $formExplanation;
    }

    public function getItem(): Item {
        return $this->item;
    }

    public function getQuantity(): int {
        return $this->quantity;
    }

    public function getCost(): int {
        return $this->cost;
    }

    public function getCustomName(): string {
        return $this->customName;
    }

    public function getLore(): array|null {
        return $this->lore;
    }

    public function getFormExplanation(): string|null {
        return $this->formExplanation;
    }

}