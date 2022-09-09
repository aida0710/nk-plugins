<?php

namespace deceitya\miningtools\calculation;

use pocketmine\item\Item;

class CheckItem {

    /**
     * @param Item $item
     * @return bool
     */
    public function isMiningTools(Item $item): bool {
        if ($item->getNamedTag()->getTag('4mining') !== null) {
            return true;
        }
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