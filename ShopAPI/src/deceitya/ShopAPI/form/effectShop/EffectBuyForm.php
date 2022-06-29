<?php

namespace deceitya\ShopAPI\form\effectShop;

use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\form\CustomForm;
use Deceitya\MiningLevel\MiningLevelAPI;
use deceitya\ShopAPI\database\effectShopAPI;
use onebone\economyapi\EconomyAPI;
use pocketmine\entity\effect\Effect;
use pocketmine\item\effectment\effectment;
use pocketmine\item\effectment\effectmentInstance;
use pocketmine\player\Player;

class EffectBuyForm extends CustomForm {

    private int $level;
    private Effect $effectment;
    private string $effectName;

    public function __construct(Player $player, int $level, Effect $effectment, string $effectName) {
        $this->level = $level;
        $this->effectment = $effectment;
        $this->effectName = $effectName;
        $this
            ->setTitle("effect Form")
            ->addElements(
                new Label("現在の所持金 -> " . EconomyAPI::getInstance()->myMoney($player) . "円\n"),
                new Label("購入価格 -> " . effectShopAPI::getInstance()->getBuy($effectName) * $this->level . "円"),
                new Label("{$effectName}を{$level}レベル付与しますか？\n"),
                new Label("§c注意 : エンチャントレベルは上書きされます(1lvを二度付与しても2lvにはならず1lvになります)§r"),
                new Label("所持しているアイテム -> " . $player->getInventory()->getItemInHand()->getName()),
            );
    }

    public function handleSubmit(Player $player): void {
        $price = effectShopAPI::getInstance()->getBuy($this->effectName) * $this->level;
        if (EconomyAPI::getInstance()->myMoney($player) <= $price) {
            $player->sendMessage("§beffect §7>> §c所持金が足りません。要求価格 -> {$price}円");
            return;
        }
        if (MiningLevelAPI::getInstance()->getLevel($player) < effectShopAPI::getInstance()->getMiningLevel($this->effectName)) {
            $player->sendForm(new effectSelectForm("§cMiningLevelが足りないためformを開けませんでした\n要求レベル ->" . effectShopAPI::getInstance()->getMiningLevel($this->effectName) . "lv"));
            return;
        }
        EconomyAPI::getInstance()->reduceMoney($player, $price);
        $player->getInventory()->addItem($player->getInventory()->getItemInHand()->addeffectment(new effectmentInstance($this->effectment, $this->level)));
        $player->sendMessage("§beffect §7>> §a{$this->effectName}を{$this->level}レベルで付与しました");
    }

}