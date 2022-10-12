<?php

namespace deceitya\ShopAPI\form\levelShop\shop4;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\ShopAPI\form\levelShop\Calculation;
use lazyperson710\core\packet\SendForm;
use lazyperson710\core\packet\SoundPacket;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;

class Weapon extends SimpleForm {

    private string $shopNumber;
    private array $contents;

    public function __construct(Player $player) {
        $this->shopNumber = basename(__DIR__);
        $this->contents = [
            VanillaItems::IRON_SWORD(),
            VanillaItems::DIAMOND_SWORD(),
            VanillaItems::BOW(),
            VanillaItems::ARROW(),
            VanillaItems::SNOWBALL(),
            VanillaItems::EGG(),
            513,
            772,
        ];
        Calculation::getInstance()->sendButton($player, $this->shopNumber, $this->contents, $this);
    }

    public function handleClosed(Player $player): void {
        SoundPacket::Send($player, 'mob.shulker.close');
        SendForm::Send($player, Calculation::getInstance()->secondBackFormClass($this->shopNumber));
    }
}
