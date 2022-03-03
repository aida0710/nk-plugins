<?php

namespace bbo51dog\bboform\element;

use JsonSerializable;
use pocketmine\player\Player;

interface Element extends JsonSerializable {

    /**
     * Called when form is submitted
     *
     * @param Player $player
     * @return mixed
     */
    public function handleSubmit(Player $player): void;
}