<?php

namespace deceitya\ShopAPI\form\element;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\element\ButtonImage;
use Deceitya\MiningLevel\MiningLevelAPI;
use deceitya\ShopAPI\database\EnchantShopAPI;
use deceitya\ShopAPI\form\enchantShop\EnchantConfirmationForm;
use deceitya\ShopAPI\form\enchantShop\EnchantSelectForm;
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
        if (MiningLevelAPI::getInstance()->getLevel($player) >= EnchantShopAPI::getInstance()->getMiningLevel($this->enchantment)) {
            $player->sendForm(new EnchantConfirmationForm($player, $this->enchantment));
        } else {
            $player->sendForm(new EnchantSelectForm("§cMiningLevelが足りないためformを開けませんでした\n要求レベル ->" . EnchantShopAPI::getInstance()->getMiningLevel($this->enchantment) . "lv"));
        }
    }
}