<?php

declare(strict_types=1);

namespace lazyperson710\core\listener;

use lazyperson710\core\packet\SendMessage\SendTip;
use pocketmine\block\inventory\AnvilInventory;
use pocketmine\block\inventory\BrewingStandInventory;
use pocketmine\block\inventory\EnchantInventory;
use pocketmine\block\inventory\LoomInventory;
use pocketmine\event\block\BlockFormEvent;
use pocketmine\event\block\BlockTeleportEvent;
use pocketmine\event\block\BrewItemEvent;
use pocketmine\event\entity\EntityExplodeEvent;
use pocketmine\event\inventory\InventoryOpenEvent;
use pocketmine\event\Listener;

class CancelEvent implements Listener {

	public function onInventoryOpen(InventoryOpenEvent $event) {
		$inventory = $event->getInventory();
		$player = $event->getPlayer();
		if ($inventory instanceof LoomInventory || $inventory instanceof EnchantInventory || $inventory instanceof BrewingStandInventory) {
			SendTip::Send($player, "このブロックのインベントリは開くことが出来ません", "Inventory", false);
			$event->cancel();
		}
		if ($inventory instanceof AnvilInventory) {
			SendTip::Send($player, "スニークしながらタップするとアイテムの修繕が可能です", "Repair", false);
			$event->cancel();
		}
	}

	public function onBlockForm(BlockFormEvent $event) {
		$event->cancel();
	}

	public function onBrewItem(BrewItemEvent $event) {
		$event->cancel();
	}

	public function onEntityExplode(EntityExplodeEvent $event) {
		$event->cancel();
	}

	public function onBlockTeleport(BlockTeleportEvent $event) {
		$event->cancel();
	}
}
