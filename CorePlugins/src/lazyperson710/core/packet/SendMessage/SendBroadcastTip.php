<?php

declare(strict_types=1);
namespace lazyperson710\core\packet\SendMessage;

use pocketmine\Server;
use pocketmine\utils\TextFormat;

class SendBroadcastTip {

	public static function Send(string $message, string $prefix) : void {
		Server::getInstance()->broadcastTip(TextFormat::AQUA . $prefix . TextFormat::GRAY . " >> " . TextFormat::YELLOW . $message);
	}
}
