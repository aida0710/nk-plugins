<?php

namespace deceitya\ShopAPI\form\effectShop;

use bbo51dog\bboform\element\Input;
use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\element\Slider;
use bbo51dog\bboform\form\CustomForm;
use deceitya\ShopAPI\database\EffectShopAPI;
use lazyperson710\core\packet\SendForm;
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
        $total = 3 * $api->getBuy($effectName) + (2 * $api->getAmplifiedMoney($effectName));
        $this
            ->setTitle("Effect Form")
            ->addElements(
                new Label("{$effectName}を付与しようとしています"),
                new Label("{$effectName}は1分ごとに{$api->getBuy($effectName)}円かかります"),
                new Label("また、1レベルにつき{$api->getAmplifiedMoney($effectName)}円増幅します"),
                new Label("例 : 2lvのエフェクトを3分購入した場、3 x {$api->getBuy($effectName)} + (2 x {$api->getAmplifiedMoney($effectName)})で{$total}円になります"),
                new Label("現在の所持金 -> " . EconomyAPI::getInstance()->myMoney($player)),
                $this->level,
                $this->time,
            );
    }

    public function handleSubmit(Player $player): void {
        if (is_numeric($this->time->getValue())) {
            $time = (int)$this->time->getValue();
        } else {
            $player->sendMessage("§bEffect §7>> §c時間は数字で入力してください");
            return;
        }
        if ($time > EffectShopAPI::getInstance()->getTimeRestriction($this->effectName)) {
            $player->sendMessage("§bEffect §7>> §c付与時間が上限を超えているため処理が中断されました");
            return;
        }
        if ($time < 1) {
            $player->sendMessage("§bEffect §7>> §c付与時間が1分未満です");
            return;
        }
        $level = (int)$this->level->getValue();
        SendForm::Send($player, (new EffectBuyForm($player, $level, $this->effect, $this->effectName, $time)));
    }
}