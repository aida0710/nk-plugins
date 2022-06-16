<?php

namespace deceitya\miningtools\extensions\enchant;

use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\form\CustomForm;
use pocketmine\block\BlockLegacyIds;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\player\Player;
use pocketmine\Server;

class EffectConfirmForm extends CustomForm {

    private int $level;
    //Money / Item;
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

    const CostItemId = BlockLegacyIds::ENCHANTMENT_TABLE;
    const CostItemNBT = "MiningToolsEnchantCostItem";

    public function __construct(Player $player) {
        //$item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 1));
        $this->setTitle("Expansion Mining Tools");
        foreach ($player->getInventory()->getItemInHand()->getEnchantments() as $enchant) {
            $enchantName = $enchant->getType()->getName();
            if ($enchantName === VanillaEnchantments::UNBREAKING()->getName()) {
                $this->level = $enchant->getLevel();
                switch ($enchant->getLevel()) {
                    case 10:
                        $this->addElements(
                            new Label("現在、耐久力エンチャントはされていません\n以下のコストを支払ってMiningToolを強化しますか？"),
                            new Label("コスト\n")
                        );
                        return;
                    case 25:
                        $this->addElements(
                            new Label("現在、耐久力エンチャントはRank.2です\n以下のコストを支払ってMiningToolを強化しますか？"),
                            new Label("コスト\n")
                        );
                        return;
                    case 35:
                        $this->addElements(
                            new Label("現在、耐久力エンチャントは最大レベルです\nエンドコンテンツとして修繕を付与することが可能です"),
                            new Label("コスト\n")
                        );
                        return;
                    default:
                        Server::getInstance()->broadcastMessage("[" . $player->getName() . "]" . __DIR__ . "の" . __LINE__ . "行目でエラーが発生しました");
                        $this->addElement(new Label("例外が発生しました"));
                        return;
                }
            }
        }
    }

    public function handleSubmit(Player $player): void {
        $player->sendForm(new EffectBuyForm($player, $this->level));
    }

}