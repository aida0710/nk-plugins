<?php

namespace deceitya\miningtools\extensions\effect;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\miningtools\element\SendEffectFormButton;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\player\Player;

class EffectSelectForm extends SimpleForm {

    public function __construct(Player $player) {
        $this
            ->setTitle("Expansion Mining Tools")
            ->setText("強化したい機能を選択してください\n付与されたMiningToolsを持った時にエフェクトが付きます")
            ->addElements(
                new SendEffectFormButton("火炎耐性", VanillaEffects::FIRE_RESISTANCE()),
                new SendEffectFormButton("水中呼吸", VanillaEffects::WATER_BREATHING()),
                new SendEffectFormButton("暗視", VanillaEffects::NIGHT_VISION()),
                new SendEffectFormButton("採掘速度上昇", VanillaEffects::HASTE()),
                new SendEffectFormButton("満腹度回復", VanillaEffects::SATURATION()),
                new SendEffectFormButton("体力増強", VanillaEffects::HEALTH_BOOST()),
                new SendEffectFormButton("再生能力", VanillaEffects::REGENERATION()),
            );
    }

}
