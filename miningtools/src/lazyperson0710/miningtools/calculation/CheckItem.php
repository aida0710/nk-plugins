<?php

declare(strict_types = 0);

namespace lazyperson0710\miningtools\calculation;

use pocketmine\item\Item;

class CheckItem {

	public function isMiningTools(Item $item) : bool {
		if ($item->getNamedTag()->getTag('gacha_mining') !== null) {
			return true;
		}
		if ($item->getNamedTag()->getTag('MiningTools_3') !== null) {
			return true;
		}
		if ($item->getNamedTag()->getTag('MiningTools_Expansion_Range') !== null) {
			return true;
		}
		return false;
	}

}
