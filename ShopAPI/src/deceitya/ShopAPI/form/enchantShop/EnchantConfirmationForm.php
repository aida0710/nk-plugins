<?php

namespace deceitya\ShopAPI\form\enchantShop;

use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\element\Slider;
use bbo51dog\bboform\form\CustomForm;
use deceitya\ShopAPI\database\EnchantShopAPI;
use onebone\economyapi\EconomyAPI;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\lang\Translatable;
use pocketmine\player\Player;
use pocketmine\Server;

class EnchantConfirmationForm extends CustomForm {

    private Slider $level;
    private string $enchantName;
    private Enchantment $enchantment;

    public function __construct(Player $player, Enchantment $enchantment) {
        $enchantName = null;
        if ($enchantment->getName() instanceof Translatable) {
            $enchantName = Server::getInstance()->getLanguage()->translate($enchantment->getName());
        }
        $this->level = new Slider("付与したいレベルにスライドして下さい", 1, EnchantShopAPI::getInstance()->getLimit($enchantment));
        $this->enchantment = $enchantment;
        $this->enchantName = $enchantName;
        $api = EnchantShopAPI::getInstance();
        $this
            ->setTitle("Enchant Form")
            ->addElements(
                new Label("{$enchantName}を付与しようとしています"),
                new Label("{$enchantName}は1レベルごとに{$api->getBuy($enchantment)}円かかります"),
                new Label("\n現在の所持金 -> " . EconomyAPI::getInstance()->myMoney($player)),
                $this->level,
            );
    }

    public function handleSubmit(Player $player): void {
        $level = (int)$this->level->getValue();
        $player->sendForm(new EnchantBuyForm($player, $level, $this->enchantment, $this->enchantName));
    }
}