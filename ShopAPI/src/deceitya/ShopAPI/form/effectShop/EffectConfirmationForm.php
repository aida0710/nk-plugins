<?php

namespace deceitya\ShopAPI\form\effectShop;

use bbo51dog\bboform\element\Input;
use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\element\Slider;
use bbo51dog\bboform\form\CustomForm;
use deceitya\ShopAPI\database\EffectShopAPI;
use onebone\economyapi\EconomyAPI;
use pocketmine\entity\effect\Effect;
use pocketmine\lang\Translatable;
use pocketmine\player\Player;
use pocketmine\Server;

class EffectConfirmationForm extends CustomForm {

    private Slider $level;
    private Input $time;
    private string $effectName;
    private Effect $effect;

    public function __construct(Player $player, Effect $effect) {
        $api = EffectShopAPI::getInstance();
        $effectName = $effect->getName();
        if ($effectName instanceof Translatable) {
            $effectName = Server::getInstance()->getLanguage()->translate($effectName);
        }
        $this->level = new Slider("付与したいレベルにスライドして下さい", 1, EffectShopAPI::getInstance()->getLevelLimit($effectName));
        $this->time = new Input("付与したい時間を入力して下さい / 単位 : 分\n付与時間上限 {$api->getTimeRestriction($effectName)}分", 100);
        $this->effect = $effect;
        $this->effectName = $effectName;
        $total = 3 * $api->getBuy($effectName) * (2 * $api->getAmplifiedMoney($effectName));
        $this
            ->setTitle("Effect Form")
            ->addElements(
                new Label("{$effectName}を付与しようとしています"),
                new Label("{$effectName}は1分ごとに{$api->getBuy($effectName)}円かかります"),
                new Label("また、1レベルにつき{$api->getAmplifiedMoney($effectName)}円増幅します(1レベルは増幅無し)"),
                new Label("例 : 2lvのエフェクトを3分購入した場、3 x {$api->getBuy($effectName)} x (2 x {$api->getAmplifiedMoney($effectName)})で{$total}円になります"),
                new Label("\n現在の所持金 -> " . EconomyAPI::getInstance()->myMoney($player)),
                $this->level,
            );
    }

    public function handleSubmit(Player $player): void {
        $level = (int)$this->level->getValue();
        $player->sendForm(new EffectBuyForm($player, $level, $this->effect, $this->effectName, $this->time->getValue()));
    }
}