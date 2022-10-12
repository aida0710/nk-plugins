<?php

namespace deceitya\ShopAPI\form\levelShop\shop2;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\ShopAPI\form\levelShop\Calculation;
use lazyperson710\core\packet\SendForm;
use lazyperson710\core\packet\SoundPacket;
use pocketmine\block\VanillaBlocks;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;

class Crop extends SimpleForm {

    private string $shopNumber;
    private array $contents;

    public function __construct(Player $player) {
        $this->shopNumber = basename(__DIR__);
        $this->contents = [
            VanillaItems::WHEAT(),
            VanillaItems::POTATO(),
            VanillaItems::CARROT(),
            VanillaItems::BEETROOT(),
            VanillaItems::SWEET_BERRIES(),
            VanillaBlocks::BAMBOO()->asItem(),
            VanillaBlocks::SUGARCANE()->asItem(),
            VanillaItems::APPLE(),
            VanillaBlocks::MELON()->asItem(),
            VanillaBlocks::PUMPKIN()->asItem(),
        ];
        Calculation::getInstance()->sendButton($player, $this->shopNumber, $this->contents, $this);
    }

    public function handleClosed(Player $player): void {
        SoundPacket::Send($player, 'mob.shulker.close');
        SendForm::Send($player, Calculation::getInstance()->secondBackFormClass($this->shopNumber));
    }
}