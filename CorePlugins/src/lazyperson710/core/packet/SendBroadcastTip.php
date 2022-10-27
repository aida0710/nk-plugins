<?php

namespace lazyperson710\core\packet;

use pocketmine\Server;
use pocketmine\utils\TextFormat;

class SendBroadcastTip {

    public static function Send(string $message, string $prefix): void {
        Server::getInstance()->broadcastTip(TextFormat::BLUE . $prefix . TextFormat::GRAY . " >> " . TextFormat::YELLOW . $message);
    }
}