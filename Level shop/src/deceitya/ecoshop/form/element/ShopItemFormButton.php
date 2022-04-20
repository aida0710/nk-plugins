<?php

namespace deceitya\ecoshop\form\element;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\element\ButtonImage;
use deceitya\ecoshop\database\LevelShopAPI;
use deceitya\ecoshop\form\PurchaseForm;
use deceitya\ecoshop\form\SellBuyForm;
use onebone\economyapi\EconomyAPI;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\player\Player;
use ree_jp\stackstorage\api\StackStorageAPI;

class ShopItemFormButton extends Button {

    private int $class;

    /**
     * @param string $text
     * @param ButtonImage|null $image
     */
    public function __construct(string $text, int $class, ?ButtonImage $image = null) {
        parent::__construct($text, $image);
        $this->class = $class;
    }

    public function handleSubmit(Player $player): void {
        $player->sendForm(new $class($this->itemId, LevelShopAPI::getInstance()->getBuy($this->itemId), LevelShopAPI::getInstance()->getSell($this->itemId)));
    }
}