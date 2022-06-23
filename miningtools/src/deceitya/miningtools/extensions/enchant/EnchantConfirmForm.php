<?php

namespace deceitya\miningtools\extensions\enchant;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\form\SimpleForm;
use pocketmine\block\BlockLegacyIds;
use pocketmine\player\Player;

class EnchantConfirmForm extends SimpleForm {

    private array $nbt = [];
    const Rank1_MoneyCost = 1500;
    const Rank2_MoneyCost = 1500;
    const Rank3_MoneyCost = 1500;

    const CostItemId = BlockLegacyIds::PACKED_ICE;
    const CostItemNBT = "MiningToolsEnchantCostItem";

    public function __construct(Player $player) {
        $upgrade = "未定義のエラー";
        $namedTag = $player->getInventory()->getItemInHand()->getNamedTag();
        if ($namedTag->getTag('MiningTools_Expansion_Enchant') !== null) {
            $this->nbt = ["MiningTools_Expansion_Enchant" => $namedTag->getInt("MiningTools_Expansion_Enchant")];
            $upgrade = match ($namedTag->getInt('MiningTools_Expansion_Enchant')) {
                1 => "現在、耐久力エンチャントはRank.2です\n以下のコストを支払ってMiningToolを強化しますか？",
                2 => "現在、耐久力エンチャントは最大レベルです\nエンドコンテンツとして修繕を付与することが可能です",
                3 => "最上位ツールの為アップグレードに対応していません",
                default => "Errorが発生しました",
            };
        } else {
            $upgrade = "現在、耐久力エンチャントはされていません\n以下のコストを支払ってMiningToolを強化しますか？";
        }
        $this
            ->setTitle("Expansion Mining Tools")
            ->setText($upgrade)
            ->addElements(new Button("アップデートする"));
    }

    public function handleSubmit(Player $player): void {
        if ($player->getInventory()->getItemInHand()->getNamedTag()->getInt('MiningTools_Expansion_Enchant') === 3) {
            return;
        }
        $player->sendForm(new EnchantBuyForm($player, $this->nbt));
    }
}