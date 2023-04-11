<?php

declare(strict_types = 0);

namespace lazyperson0710\EffectItems\items\interactListener;

use lazyperson0710\LoginBonus\form\BonusForm;
use lazyperson710\core\packet\SendForm;
use lazyperson710\core\packet\SoundPacket;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemUseEvent;

class LoginBonusItem {

	public static function execution(PlayerItemUseEvent|PlayerInteractEvent $event) : void {
		$player = $event->getPlayer();
		$event->cancel();
		SoundPacket::Send($player, 'sign.dye.use');
		SendForm::Send($player, new BonusForm($player));
	}

}
