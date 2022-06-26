<?php

namespace deceitya\miningtools\extensions\effect;

use bbo51dog\bboform\form\CustomForm;
use pocketmine\block\BlockLegacyIds;
use pocketmine\entity\effect\Effect;
use pocketmine\player\Player;

class EffectConfirmForm extends CustomForm {

    private Effect $effect;

    public const Rare = 250000;
    public const Epic = 500000;
    public const Legendary = 1000000;
    public const Rare_ItemCost = 1;
    public const Epic_ItemCost = 2;
    public const Legendary_ItemCost = 3;

    public const CostItemId = BlockLegacyIds::PRISMARINE;
    public const CostItemNBT = "MiningToolsEffectCostItem";

    public function __construct(Effect $effect) {
        $this->effect = $effect;
    }

    public function handleSubmit(Player $player): void {
        $player->sendForm(new EffectBuyForm($player, $this->effect));
    }

}