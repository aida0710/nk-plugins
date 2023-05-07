<?php

declare(strict_types = 0);

namespace lazyperson710\core\listener;

use lazyperson0710\PlayerSetting\object\PlayerSettingPool;
use lazyperson0710\PlayerSetting\object\settings\normal\EnduranceWarningSetting;
use lazyperson710\core\packet\SoundPacket;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\item\Durable;

class EnduranceWarning implements Listener {

	/**
	 * @priority HIGH
	 */
	public function onBreak(BlockBreakEvent $event) : void {
		$player = $event->getPlayer();
		$item = $event->getItem();
		if ($item instanceof Durable) {
			$value = $item->getMaxDurability() - $item->getDamage();
			$value--;
			$player->sendPopup("§bMining §7>> §a残りの耐久値は{$value}です");
			if (PlayerSettingPool::getInstance()->getSettingNonNull($player)->getSetting(EnduranceWarningSetting::getName())?->getValue() === true) {
				if ($value === 50) {
					$player->sendTitle("§c所持しているアイテムの\n耐久が50を下回りました");
					SoundPacket::Send($player, 'respawn_anchor.deplete');
				}
				if ($value === 15) {
					$player->sendTitle("§c所持しているアイテムの\n耐久が15を下回りました");
					SoundPacket::Send($player, 'respawn_anchor.deplete');
				}
			}
		}
	}
}
