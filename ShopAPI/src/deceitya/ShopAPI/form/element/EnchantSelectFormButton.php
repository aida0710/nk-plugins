<?php

namespace deceitya\ShopAPI\form\element;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\element\ButtonImage;
use Deceitya\MiningLevel\MiningLevelAPI;
use deceitya\ShopAPI\database\EnchantShopAPI;
use deceitya\ShopAPI\form\enchantShop\EnchantConfirmationForm;
use deceitya\ShopAPI\form\enchantShop\EnchantSelectForm;
use lazyperson710\core\packet\SendForm;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\player\Player;

class EnchantSelectFormButton extends Button {

    private Enchantment $enchantment;
    private string $enchantName;

    /**
     * @param string           $text
     * @param Enchantment      $enchantment
     * @param string           $enchantName
     * @param ButtonImage|null $image
     */
    public function __construct(string $text, Enchantment $enchantment, string $enchantName, ?ButtonImage $image = null) {
        parent::__construct($text, $image);
        $this->enchantment = $enchantment;
        $this->enchantName = $enchantName;
    }

    public function handleSubmit(Player $player): void {
        if (MiningLevelAPI::getInstance()->getLevel($player) >= EnchantShopAPI::getInstance()->getMiningLevel($this->enchantName)) {
            SendForm::Send($player, (new EnchantConfirmationForm($player, $this->enchantment)));
        } else {
            SendForm::Send($player, (new EnchantSelectForm("§cMiningLevelが足りないためformを開けませんでした\n要求レベル ->" . EnchantShopAPI::getInstance()->getMiningLevel($this->enchantName) . "lv")));
        }
    }
}