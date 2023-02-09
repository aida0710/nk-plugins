<?php

declare(strict_types = 1);
namespace lazyperson0710\EffectItems\items\damageListener;

use Deceitya\Flytra\Main;
use lazyperson710\core\packet\SendNoSoundMessage\SendNoSoundTip;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\Durable;
use pocketmine\player\GameMode;
use pocketmine\player\Player;

class DefensiveStone {

	public static function execution(EntityDamageEvent $event, Player $player) : void {
		if (Main::getInstance()->checkFly($player, $player->getWorld(), $player->getArmorInventory()->getChestplate()) === true) return;
		if (($event->getCause() === EntityDamageEvent::CAUSE_FALL)) {
			$armorInventory = $player->getArmorInventory();
			for ($i = 0, $size = $armorInventory->getSize(); $i < $size; ++$i) {
				$item = clone $armorInventory->getItem($i);
				if ($item->getNamedTag()->getTag('DefensiveStone') !== null) {//DefensiveStone
					if ($item instanceof Durable) {
						if ($player->getGamemode() !== GameMode::CREATIVE()) {
							$damage = $event->getBaseDamage() * 2;
							$item->applyDamage($damage);
							$armorInventory->setItem($i, $item);
						} else $damage = '0';
						SendNoSoundTip::Send($player, '落下ダメージが無効化されました！耐久 -' . $damage, 'Durable', false, 'block.lantern.break');
						$event->cancel();
					}
					return;
				}
			}
		}
	}
}
