<?php

namespace deceitya\ShopAPI\form\element;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\element\ButtonImage;
use deceitya\ShopAPI\form\effectShop\EffectConfirmationForm;
use pocketmine\entity\effect\Effect;
use pocketmine\player\Player;

class EffectSelectFormButton extends Button {

    private Effect $effect;

    /**
     * @param string $text
     * @param Effect $effect
     * @param ButtonImage|null $image
     */
    public function __construct(string $text, Effect $effect, ?ButtonImage $image = null) {
        parent::__construct($text, $image);
        $this->effect = $effect;
    }

    public function handleSubmit(Player $player): void {
        $player->sendForm(new EffectConfirmationForm($player, $this->effect));
    }
}