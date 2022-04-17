<?php

namespace deceitya\ecoshop\form\element;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\element\ButtonImage;
use deceitya\ecoshop\database\LevelShopAPI;
use deceitya\ecoshop\form\SellBuyForm;
use pocketmine\player\Player;

class SellBuyItemFormButton extends Button {

    private int $itemId;

    /**
     * @param string $text
     * @param ButtonImage|null $image
     */
    public function __construct(string $text, int $itemId, ?ButtonImage $image = null) {
        parent::__construct($text, $image);
        $this->itemId = $itemId;
    }

    public function handleSubmit(Player $player): void {
        $player->sendForm(new SellBuyForm($this->itemId, LevelShopAPI::getInstance()->getBuy($this->itemId), LevelShopAPI::getInstance()->getBuy($this->itemId)));
    }
}