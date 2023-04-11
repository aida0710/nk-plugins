<?php

declare(strict_types = 0);

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

class HasteItem {

	public static function execution(PlayerItemUseEvent|PlayerInteractEvent $event, Item $item) : void {
		$event->cancel();
		$player = $event->getPlayer();
		if (PlayerItemEvent::checkInterval($player) === false) return;
		if ($player->getGamemode() !== GameMode::CREATIVE()) {
			$player->getInventory()->removeItem($item->setCount(1));
		}
		$effect = new EffectInstance(VanillaEffects::HASTE(), 20 * 30, 50, false);
		AddEffectPacket::Add($player, $effect, VanillaEffects::HASTE(), true);
		$effect = new EffectInstance(VanillaEffects::SLOWNESS(), 20 * 90, 2, false);
		AddEffectPacket::Add($player, $effect, VanillaEffects::SLOWNESS(), true);
		SoundPacket::Send($player, 'item.trident.return');
	}
}
