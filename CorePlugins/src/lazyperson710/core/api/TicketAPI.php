<?php

namespace lazyperson710\core\api;

use pocketmine\player\Player;

class TicketAPI {

    private SQLDatabase $db;

    private static TicketAPI $instance;

    public function __construct() {
    }

    public static function getInstance(): TicketAPI {
        if (!isset(self::$instance)) {
            self::$instance = new TicketAPI();
        }
        return self::$instance;
    }

    public function createTicketDatabase(Player $player): bool{

    }

    public function getTicket(Player $player): int{

    }

}

