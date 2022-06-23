<?php

namespace deceitya\ShopAPI\form\enchantShop;

use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\element\Slider;
use bbo51dog\bboform\form\CustomForm;
use deceitya\ShopAPI\database\EnchantShopAPI;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\lang\Translatable;
use pocketmine\player\Player;
use pocketmine\Server;

class EnchantConfirmationForm extends CustomForm {

    public function __construct(Player $player, Enchantment $enchant) {
        $enchantName = null;
        if ($enchant->getName() instanceof Translatable) {
            $enchantName = Server::getInstance()->getLanguage()->translate($enchant->getName());
        }
        $this
            ->setTitle("Enchant Form")
            ->addElements(
                new Label("{$enchantName}を付与しようとしています"),
                new Slider("付与したいレベルにスライドして下さい", 1, EnchantShopAPI::getInstance()->getLimit($enchant))
            );
    }
}