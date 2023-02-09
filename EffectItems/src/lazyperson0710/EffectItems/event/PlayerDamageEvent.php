<?php

declare(strict_types = 1);
namespace lazyperson0710\EffectItems\event;

use lazyperson0710\EffectItems\items\damageListener\DefensiveStone;
use lazyperson710\core\packet\SendNoSoundMessage\SendNoSoundTip;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\player\Player;

class PlayerDamageEvent implements Listener {

	public function onEntityDamage(EntityDamageEvent $event) {
		if ($event->isCancelled()) return;
		$player = $event->getEntity();
		if (!$player instanceof Player) return;
		DefensiveStone::execution($event, $player);
		if ($event->getCause() == EntityDamageEvent::CAUSE_BLOCK_EXPLOSION) {
			SendNoSoundTip::Send($player, '爆発ダメージが無効化されました', 'Explosion', true);
			$event->cancel();
		}
	}
}
