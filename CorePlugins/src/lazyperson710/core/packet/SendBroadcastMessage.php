<?php

namespace lazyperson710\core\packet;

use pocketmine\Server;
use pocketmine\utils\TextFormat;

class SendBroadcastMessage {

    public static function Send(string $message, string $prefix): void {
        Server::getInstance()->broadcastMessage(TextFormat::BLUE . $prefix . TextFormat::GRAY . " >> " . TextFormat::YELLOW . $message);
    }
}