<?php

namespace deceitya\ShopAPI\form\enchantShop;

use bbo51dog\bboform\form\CustomForm;
use deceitya\ShopAPI\database\EnchantShopAPI;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\player\Player;

class EnchantBuyForm extends CustomForm {

    private int $level;
    private Enchantment $enchantment;
    private string $enchantName;

    public function __construct(Player $player, int $level, Enchantment $enchantment, string $enchantName) {
        $this->level = $level;
        $this->enchantment = $enchantment;
        $this->enchantName = $enchantName;
        //現在しょじしてるものとか//しょじきんとか
        //価格とか、付与するレベルとか
        //上書きされるのでレベルの蓄積はしないということ
    }

    public function handleSubmit(Player $player): void {
        $price = EnchantShopAPI::getInstance()->getBuy($this->enchantment) * $this->level;
    }

}