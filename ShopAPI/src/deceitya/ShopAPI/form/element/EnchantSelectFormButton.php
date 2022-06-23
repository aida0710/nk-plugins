<?php

namespace deceitya\ShopAPI\form\element;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\element\ButtonImage;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\player\Player;

class EnchantSelectFormButton extends Button {

    private Enchantment $enchantment;

    /**
     * @param string $text
     * @param Enchantment $enchantment
     * @param ButtonImage|null $image
     */
    public function __construct(string $text, Enchantment $enchantment, ?ButtonImage $image = null) {
        parent::__construct($text, $image);
        $this->enchantment = $enchantment;
    }

    public function handleSubmit(Player $player): void {
        $player->sendForm(new EnchantConfirmationForm($player, $this->enchantment));
    }
}