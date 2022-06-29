<?php

namespace deceitya\ShopAPI\form\effectShop;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\ShopAPI\database\effectShopAPI;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\lang\Translatable;
use pocketmine\Server;

class EffectSelectForm extends SimpleForm {

    public function __construct(?string $error = "") {
        $effects = [
            VanillaEffects::HASTE(),
            VanillaEffects::SPEED(),
            VanillaEffects::REGENERATION(),
            VanillaEffects::NIGHT_VISION(),
        ];
        $api = effectShopAPI::getInstance();
        $this
            ->setTitle("effect Form")
            ->setText("§7せつめい\n{$error}");
        foreach ($effects as $effect) {
            $effectName = $effect->getName();
            if ($effectName instanceof Translatable) {
                $effectName = Server::getInstance()->getLanguage()->translate($effectName);
            }
            $this->addElement(new effectSelectFormButton("{$effectName} 価格 - 毎lv.{$api->getBuy($effectName)}\nMiningLevel制限{$api->getMiningLevel($effectName)} | 付与レベル制限 - {$api->getLimit($effectName)}以下", $effect, $effectName));
        }
    }

}