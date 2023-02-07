<?php

declare(strict_types=1);
namespace lazyperson0710\EffectItems\items\interactListener\effect;

use lazyperson0710\EffectItems\event\PlayerItemEvent;
use lazyperson710\core\packet\AddEffectPacket;
use lazyperson710\core\packet\SoundPacket;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\item\Item;
use pocketmine\player\GameMode;
use function mt_rand;

class CoralButterfly {

	public static function execution(PlayerItemUseEvent|PlayerInteractEvent $event, Item $item) : void {
		$event->cancel();
		$player = $event->getPlayer();
		if (PlayerItemEvent::checkInterval($player) === false) return;
		if ($player->getGamemode() !== GameMode::CREATIVE()) {
			$player->getInventory()->removeItem($item->setCount(1));
		}
		if (mt_rand(1, 2) === 1) {
			$effect = new EffectInstance(VanillaEffects::HASTE(), 20 * 60 * 15, 50, false);
			AddEffectPacket::Add($player, $effect, VanillaEffects::HASTE(), true);
		} else {
			$player->kill();
		}
		SoundPacket::Send($player, 'item.trident.return');
	}

}
