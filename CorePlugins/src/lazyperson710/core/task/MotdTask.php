<?php

declare(strict_types = 0);

namespace lazyperson710\core\task;

use pocketmine\scheduler\Task;
use pocketmine\Server;

class MotdTask extends Task {

    private $original;
    private $change;
    private $flag = true;

    public function __construct($original, $change) {
        $this->original = $original;
        $this->change = $change;
    }

    public function onRun() : void {
        $motd = $this->flag ? $this->original : $this->change;
        Server::getInstance()->getNetwork()->setName($motd);
        $this->flag = !$this->flag;
    }
}
