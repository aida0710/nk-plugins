<?php

declare(strict_types = 0);

namespace lazyperson710\core\packet\SendMessage;

use pocketmine\Server;
use pocketmine\utils\TextFormat;

class SendBroadcastMessage {

    public static function Send(string $message, string $prefix) : void {
        Server::getInstance()->broadcastMessage(TextFormat::AQUA . $prefix . TextFormat::GRAY . ' >> ' . TextFormat::YELLOW . $message);
    }
}
