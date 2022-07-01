<?php

namespace deceitya\miningtools\extensions\enchant\fortune;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\form\SimpleForm;
use pocketmine\player\Player;

class FortuneEnchantConfirmForm extends SimpleForm {

    private array $nbt = [];
    public const Rank1_MoneyCost = 1500;
    public const Rank2_MoneyCost = 1500;
    public const Rank3_MoneyCost = 1500;

    public const Rank1_ItemCost = 1;
    public const Rank2_ItemCost = 3;
    public const Rank3_ItemCost = 8;

    public function __construct(Player $player) {
        $namedTag = $player->getInventory()->getItemInHand()->getNamedTag();
        if ($namedTag->getTag('MiningTools_Expansion_FortuneEnchant') !== null) {
            $this->nbt = ["MiningTools_Expansion_FortuneEnchant" => $namedTag->getInt("MiningTools_Expansion_FortuneEnchant")];
            $upgrade = match ($namedTag->getInt('MiningTools_Expansion_FortuneEnchant')) {
                1 => "現在、幸運エンチャントはRank.1です\n以下のコストを支払ってMiningToolを強化しますか？",
                2 => "現在、幸運エンチャントはRank.2です\n以下のコストを支払ってMiningToolを強化しますか？",
                3 => "最上位ツールの為アップグレードに対応していません",
                default => "Errorが発生しました",
            };
        } else {
            $upgrade = "現在、シルクタッチエンチャントが付与されています\n幸運エンチャントに変換することが可能です\n以下のコストを支払ってMiningToolを強化しますか？";
        }
        $this
            ->setTitle("Expansion Mining Tools")
            ->setText($upgrade)
            ->addElements(new Button("アップデートする"));
    }

    public function handleSubmit(Player $player): void {
        if ($player->getInventory()->getItemInHand()->getNamedTag()->getTag('MiningTools_Expansion_FortuneEnchant') !== null) {
            if ($player->getInventory()->getItemInHand()->getNamedTag()->getInt('MiningTools_Expansion_FortuneEnchant') === 3) {
                return;
            }
        }
        $player->sendForm(new FortuneEnchantBuyForm($player, $this->nbt));
    }
}