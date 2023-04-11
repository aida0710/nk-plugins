<?php

declare(strict_types = 0);

namespace lazyperson710\core\listener;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerBlockPickEvent;

class VanillaPickBlock implements Listener {

	/**
	 * @priority LOWEST
	 */
	public function onPlayerPickBlock(PlayerBlockPickEvent $event) : void {
		$event->cancel();
		$player = $event->getPlayer();
		if ($player->isSpectator()) {
			return;
		}
		$inventory = $player->getInventory();
		$resultItem = $event->getResultItem();
		$hatBarSize = $inventory->getHotbarSize();
		$originSlot = -1;
		foreach ($inventory->getContents() as $i => $item) { //Find origin slot (with exact-check, and without count-check)
			if ($resultItem->equals($item, true, true)) {
				$resultItem = $item;
				$originSlot = $i;
				break;
			}
		}
		if ($originSlot >= 0 && $originSlot < $hatBarSize) { //If origin item in hotBar, set held slot to origin slot
			$inventory->setHeldItemIndex($originSlot);
			return;
		}
		$targetItem = $inventory->getItemInHand();
		$targetSlot = $inventory->getHeldItemIndex();
		if (!$targetItem->isNull()) {
			for ($i = 0; $i < $hatBarSize; ++$i) { //Find empty hotBar slot
				$item = $inventory->getItem($i);
				if ($item->isNull()) {
					$targetItem = $item;
					$targetSlot = $i;
					$inventory->setHeldItemIndex($targetSlot);
					break;
				}
			}
		}
		if ($originSlot !== -1) { //If found origin item, swap target slot with origin slot
			$inventory->setItem($targetSlot, $resultItem);
			$inventory->setItem($originSlot, $targetItem);
		} elseif ($player->isCreative()) { //If not found origin item and player is creative mode, give item.
			$inventory->setItem($targetSlot, $resultItem);
			if (!$targetItem->isNull()) { //If target item is not null, return target item into inventory.
				$inventory->addItem($targetItem);
			}
		}
	}
}
