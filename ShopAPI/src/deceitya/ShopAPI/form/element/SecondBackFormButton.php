<?php

namespace deceitya\ShopAPI\form\element;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\element\ButtonImage;
use lazyperson710\core\packet\SendForm;
use lazyperson710\core\packet\SoundPacket;
use pocketmine\form\Form;
use pocketmine\player\Player;

class SecondBackFormButton extends Button {

    private Form $shopClass;

    /**
     * @param string           $text
     * @param Form             $shopClass
     * @param ButtonImage|null $image
     */
    public function __construct(string $text, form $shopClass, ?ButtonImage $image = null) {
        parent::__construct($text, $image);
        $this->shopClass = $shopClass;
    }

    public function handleSubmit(Player $player): void {
        SendForm::Send($player, $this->shopClass);
        SoundPacket::Send($player, 'mob.shulker.close');
    }
}