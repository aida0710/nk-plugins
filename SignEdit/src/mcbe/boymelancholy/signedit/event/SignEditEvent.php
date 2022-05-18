<?php

declare(strict_types=1);
namespace mcbe\boymelancholy\signedit\event;

use pocketmine\block\BaseSign;
use pocketmine\event\Event;

abstract class SignEditEvent extends Event {

    protected BaseSign $signBlock;

    public function getSign(): BaseSign {
        return $this->signBlock;
    }
}