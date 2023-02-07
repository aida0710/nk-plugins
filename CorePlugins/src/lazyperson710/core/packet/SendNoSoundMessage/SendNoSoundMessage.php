<?php

declare(strict_types=1);
namespace lazyperson710\core\packet\SendNoSoundMessage;

use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class SendNoSoundMessage {

	public static function Send(Player $player, string $message, string $prefix, bool $success) : void {
		if ($success === true) {
			$type = TextFormat::GREEN;
		} else {
			$type = TextFormat::RED;
		}
		$player->sendMessage(TextFormat::AQUA . $prefix . TextFormat::GRAY . " >> " . $type . $message);
	}
}
