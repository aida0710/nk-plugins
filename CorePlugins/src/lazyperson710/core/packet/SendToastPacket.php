<?php

declare(strict_types = 0);

namespace lazyperson710\core\packet;

use pocketmine\network\mcpe\protocol\ToastRequestPacket;
use pocketmine\player\Player;

class SendToastPacket {

	public static function Send(Player $player, string $title, string $body) : void {
		$player->getNetworkSession()->sendDataPacket(ToastRequestPacket::create($title, $body));
		SoundPacket::Send($player, 'random.toast');
	}
}
