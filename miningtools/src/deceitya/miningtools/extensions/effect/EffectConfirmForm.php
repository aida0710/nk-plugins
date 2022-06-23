<?php

namespace deceitya\miningtools\extensions\effect;

use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\form\CustomForm;
use pocketmine\block\BlockLegacyIds;
use pocketmine\entity\effect\Effect;
use pocketmine\player\Player;

class EffectConfirmForm extends CustomForm {

    private int $level;
    const Money_FIRE_RESISTANCE = 15000;
    const Money_WATER_BREATHING = 15000;
    const Money_NIGHT_VISION = 15000;
    const Money_HASTE = 15000;
    const Money_SATURATION = 15000;
    const Money_HEALTH_BOOST = 15000;
    const Money_REGENERATION = 15000;
    const Item_FIRE_RESISTANCE = 15000;
    const Item_WATER_BREATHING = 15000;
    const Item_NIGHT_VISION = 15000;
    const Item_HASTE = 15000;
    const Item_SATURATION = 15000;
    const Item_HEALTH_BOOST = 15000;
    const Item_REGENERATION = 15000;

    const CostItemId = BlockLegacyIds::PRISMARINE;
    const CostItemNBT = "MiningToolsEffectCostItem";

    public function __construct(Player $player, Effect $effect) {
        $this->addElements(
            new Label("現在、耐久力エンチャントはされていません\n以下のコストを支払ってMiningToolを強化しますか？"),
            new Label("コスト\n")
        );
        $this->setTitle("Expansion Mining Tools");
        $this->addElement(new Label("例外が発生しました"));
    }

    public function handleSubmit(Player $player): void {
        $player->sendForm(new EffectBuyForm($player, $this->level));
    }

}