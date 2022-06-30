<?php

namespace deceitya\ShopAPI\form\element;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\element\ButtonImage;
use Deceitya\MiningLevel\MiningLevelAPI;
use deceitya\ShopAPI\database\EnchantShopAPI;
use deceitya\ShopAPI\form\effectShop\EffectConfirmationForm;
use deceitya\ShopAPI\form\effectShop\EffectSelectForm;
use pocketmine\entity\effect\Effect;
use pocketmine\player\Player;

class EffectSelectFormButton extends Button {

    private Effect $effect;
    private string $effectName;

    /**
     * @param string $text
     * @param Effect $effect
     * @param string $effectName
     * @param ButtonImage|null $image
     */
    public function __construct(string $text, Effect $effect, string $effectName, ?ButtonImage $image = null) {
        parent::__construct($text, $image);
        $this->effect = $effect;
        $this->effectName = $effectName;
    }

    public function handleSubmit(Player $player): void {
        if (MiningLevelAPI::getInstance()->getLevel($player) >= EnchantShopAPI::getInstance()->getMiningLevel($this->effectName)) {
            $player->sendForm(new EffectConfirmationForm($player, $this->effect));
        } else {
            $player->sendForm(new EffectSelectForm("§cMiningLevelが足りないためformを開けませんでした\n要求レベル ->" . EnchantShopAPI::getInstance()->getMiningLevel($this->effect) . "lv"));
        }
    }
}