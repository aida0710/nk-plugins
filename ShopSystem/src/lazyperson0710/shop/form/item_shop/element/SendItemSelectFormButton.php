<?php

declare(strict_types = 1);

namespace lazyperson0710\shop\form\item_shop\element;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\element\ButtonImage;
use lazyperson0710\shop\form\item_shop\future\LevelCheck;
use lazyperson0710\shop\form\item_shop\ItemSelectForm;
use pocketmine\player\Player;

class SendItemSelectFormButton extends Button {

    public function __construct(
        string $text,
        private int $shopNumber,
        private string $category,
        private int $restrictionLevel,
        ?ButtonImage $image = null,
    ) {
        parent::__construct($text, $image);
    }

    public function handleSubmit(Player $player) : void {
        LevelCheck::sendForm($player, new ItemSelectForm($player, $this->shopNumber, $this->category), $this->restrictionLevel);
    }
}
