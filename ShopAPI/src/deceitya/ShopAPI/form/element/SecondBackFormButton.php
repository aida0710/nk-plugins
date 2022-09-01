<?php

namespace deceitya\ShopAPI\form\element;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\element\ButtonImage;
use lazyperson710\core\packet\SendForm;
use pocketmine\player\Player;

class SecondBackFormButton extends Button {

    private int $shopNumber;

    /**
     * @param string           $text
     * @param int              $shopNumber
     * @param ButtonImage|null $image
     */
    public function __construct(string $text, int $shopNumber, ?ButtonImage $image = null) {
        parent::__construct($text, $image);
        $this->shopNumber = $shopNumber;
    }

    public function handleSubmit(Player $player): void {
        $class = '\deceitya\ShopAPI\form\levelShop\shop' . $this->shopNumber . '\Shop' . $this->shopNumber . 'Form';
        SendForm::Send($player, (new $class));
    }
}