<?php

namespace deceitya\ShopAPI\form\effectShop;

use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\element\Slider;
use bbo51dog\bboform\form\CustomForm;
use deceitya\ShopAPI\database\effectShopAPI;
use onebone\economyapi\EconomyAPI;
use pocketmine\entity\effect\Effect;
use pocketmine\lang\Translatable;
use pocketmine\player\Player;
use pocketmine\Server;

class EffectConfirmationForm extends CustomForm {

    private Slider $level;
    private string $effectName;
    private Effect $effect;

    public function __construct(Player $player, Effect $effect) {
        $effectName = $effect->getName();
        if ($effectName instanceof Translatable) {
            $effectName = Server::getInstance()->getLanguage()->translate($effectName);
        }
        $this->level = new Slider("付与したいレベルにスライドして下さい", 1, effectShopAPI::getInstance()->getLimit($effectName));
        $this->effect = $effect;
        $this->effectName = $effectName;
        $api = effectShopAPI::getInstance();
        $this
            ->setTitle("effect Form")
            ->addElements(
                new Label("{$effectName}を付与しようとしています"),
                new Label("{$effectName}は1レベルごとに{$api->getBuy($effectName)}円かかります"),
                new Label("\n現在の所持金 -> " . EconomyAPI::getInstance()->myMoney($player)),
                $this->level,
            );
    }

    public function handleSubmit(Player $player): void {
        $level = (int)$this->level->getValue();
        $player->sendForm(new effectBuyForm($player, $level, $this->effect, $this->effectName));
    }
}