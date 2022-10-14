<?php

namespace lazyperson0710\ShopAPI\form\element;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\element\ButtonImage;
use lazyperson0710\ShopAPI\form\effectShop\EffectConfirmationForm;
use lazyperson710\core\packet\SendForm;
use pocketmine\entity\effect\Effect;
use pocketmine\player\Player;

class EffectSelectFormButton extends Button {

    private Effect $effect;

    /**
     * @param string           $text
     * @param Effect           $effect
     * @param ButtonImage|null $image
     */
    public function __construct(string $text, Effect $effect, ?ButtonImage $image = null) {
        parent::__construct($text, $image);
        $this->effect = $effect;
    }

    public function handleSubmit(Player $player): void {
        SendForm::Send($player, (new EffectConfirmationForm($player, $this->effect)));
    }
}