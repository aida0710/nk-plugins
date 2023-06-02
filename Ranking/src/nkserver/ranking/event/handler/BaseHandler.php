<?php

declare(strict_types = 0);

namespace nkserver\ranking\event\handler;

use pocketmine\event\Event;

interface BaseHandler {

    public static function getTarget() : string;

    public static function handleEvent(Event $ev) : void;
}
