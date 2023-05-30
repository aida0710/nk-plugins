<?php

declare(strict_types = 1);

namespace lazyperson0710\shop\form\item_shop;

use bbo51dog\bboform\form\CustomForm;
use lazyperson0710\shop\object\ItemShopObject;
use pocketmine\player\Player;

class ItemSellForm extends CustomForm {

	public function __construct(Player $player, ItemShopObject $item) {
	}
}
