<?php

namespace deceitya\miningtools\extensions\enchant\unbreaking;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\form\SimpleForm;
use pocketmine\player\Player;

class UnbreakingEnchantConfirmForm extends SimpleForm {

    private array $nbt = [];
    public const Rank1_MoneyCost = 1500;
    public const Rank2_MoneyCost = 1500;
    public const Rank3_MoneyCost = 1500;

    public function __construct(Player $player) {
        $namedTag = $player->getInventory()->getItemInHand()->getNamedTag();
        if ($namedTag->getTag('MiningTools_Expansion_UnbreakingEnchant') !== null) {
            $this->nbt = ["MiningTools_Expansion_UnbreakingEnchant" => $namedTag->getInt("MiningTools_Expansion_UnbreakingEnchant")];
            $upgrade = match ($namedTag->getInt('MiningTools_Expansion_UnbreakingEnchant')) {
                1 => "現在、耐久力エンチャントはRank.1です\n以下のコストを支払ってMiningToolを強化しますか？",
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
        if ($player->getInventory()->getItemInHand()->getNamedTag()->getTag('MiningTools_Expansion_UnbreakingEnchant') !== null) {
            if ($player->getInventory()->getItemInHand()->getNamedTag()->getInt('MiningTools_Expansion_UnbreakingEnchant') === 3) {
                return;
            }
        }
        $player->sendForm(new UnbreakingEnchantBuyForm($player, $this->nbt));
    }
}