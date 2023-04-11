<?php

declare(strict_types = 0);

namespace lazyperson0710\EffectItems\items\interactListener;

use lazyperson0710\EffectItems\event\PlayerItemEvent;
use lazyperson710\core\packet\SendNoSoundMessage\SendNoSoundMessage;
use lazyperson710\core\packet\SoundPacket;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\item\Item;
use pocketmine\player\GameMode;
use function mt_rand;

class LuckyExpCoin {

	public static function execution(PlayerItemUseEvent|PlayerInteractEvent $event, Item $item) : void {
		$event->cancel();
		$player = $event->getPlayer();
		if (PlayerItemEvent::checkInterval($player) === false) return;
		if ($player->getGamemode() !== GameMode::CREATIVE()) {
			$player->getInventory()->removeItem($item->setCount(1));
		}
		$addXp = match (mt_rand(1, 10)) {
			1 => 10,
			2 => 30,
			3 => 80,
			4 => 140,
			5 => 200,
			6 => 250,
			7 => 280,
			8 => 320,
			9 => 400,
			10 => 800,
		};
		$player->getXpManager()->addXp($addXp);
		SendNoSoundMessage::Send($player, "{$addXp}xpが当たりました！", 'LuckyExpCoin', true);
		SoundPacket::Send($player, 'item.trident.return');
	}
}
