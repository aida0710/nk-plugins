<?php

declare(strict_types = 0);
namespace lazyperson710\core\listener;

use lazyperson0710\PlayerSetting\object\PlayerSettingPool;
use lazyperson0710\PlayerSetting\object\settings\normal\DirectDropItemStorageSetting;
use lazyperson710\core\packet\SendNoSoundMessage\SendNoSoundActionBarMessage;
use pocketmine\block\BlockLegacyIds;
use pocketmine\block\VanillaBlocks;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\item\Item;
use pocketmine\player\Player;
use ree_jp\stackstorage\api\StackStorageAPI;

class DirectInventory implements Listener {

	/**
	 * @priority HIGHEST
	 */
	public function onBreak(BlockBreakEvent $event) {
		if ($event->isCancelled()) return;
		$drops = $event->getDrops();
		$player = $event->getPlayer();
		$event->setDrops([]);
		DirectInventory::onDrop($player, $drops);
	}

	static public function onDrop(Player $player, array $drops) : void {
		if (PlayerSettingPool::getInstance()->getSettingNonNull($player)->getSetting(DirectDropItemStorageSetting::getName())?->getValue() === true) {
			foreach ($drops as $item) {
				if ((new DirectInventory())->notStorageItem($player, $item) === false) continue;
				StackStorageAPI::$instance->add($player->getXuid(), $item);
				SendNoSoundActionBarMessage::Send($player, $item->getName() . 'が倉庫にしまわれました', 'Storage', true);
			}
		} else {
			foreach ($drops as $item) {
				if ((new DirectInventory())->notStorageItem($player, $item) === false) continue;
				if ($player->getInventory()->canAddItem($item)) {
					$player->getInventory()->addItem($item);
				} else {
					StackStorageAPI::$instance->add($player->getXuid(), $item);
					SendNoSoundActionBarMessage::Send($player, 'インベントリに空きが無いため' . $item->getName() . 'が倉庫にしまわれました', 'Storage', true);
				}
			}
		}
	}

	//debug ここら辺の処理は不安定な可能性があるためデバッグしてください
	private function notStorageItem(Player $player, Item $item) : bool {
		if ($item->getId() === BlockLegacyIds::AIR) return false;
		switch (true) {
			case $item->getId() === VanillaBlocks::SHULKER_BOX()->asItem()->getId():
			case $item->getId() === VanillaBlocks::DYED_SHULKER_BOX()->asItem()->getId():
				if ($player->getInventory()->canAddItem($item)) {
					$player->getInventory()->addItem($item);
				} else {
					$player->dropItem($item);
					SendNoSoundActionBarMessage::Send($player, $item->getName() . 'を直接ストレージに格納することは出来ない為ドロップされました', 'Storage', false);
				}
				return false;
		}
		return true;
	}
}
