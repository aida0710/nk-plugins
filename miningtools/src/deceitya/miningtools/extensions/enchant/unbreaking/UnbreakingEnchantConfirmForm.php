<?php

namespace deceitya\miningtools\extensions\enchant\unbreaking;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\form\SimpleForm;
use pocketmine\player\Player;
use pocketmine\Server;

class UnbreakingEnchantConfirmForm extends SimpleForm {

    private array $nbt = [];
    public const Rank1_MoneyCost = 1200000;
    public const Rank2_MoneyCost = 2500000;
    public const Rank3_MoneyCost = 15000000;

    public const Rank1_ItemCost = 1;
    public const Rank2_ItemCost = 8;
    public const Rank3_ItemCost = 20;

    public function __construct(Player $player) {
        $namedTag = $player->getInventory()->getItemInHand()->getNamedTag();
        $cost = "";
        if ($namedTag->getTag('MiningTools_Expansion_UnbreakingEnchant') !== null) {
            $this->nbt = ["MiningTools_Expansion_UnbreakingEnchant" => $namedTag->getInt("MiningTools_Expansion_UnbreakingEnchant")];
            switch ($namedTag->getInt('MiningTools_Expansion_UnbreakingEnchant')) {
                case 1:
                    $upgrade = "現在耐久エンチャントはRank.1です\n\n強化効果 : 耐久25から耐久35に強化\n\n以下のコストを支払ってMiningToolを強化しますか？";
                    $cost = "コストは" . self::Rank2_MoneyCost . "円と\nMiningToolsEnchantCostItem " . self::Rank2_ItemCost . "個のアイテム\nをインベントリに保持している必要があります";
                    break;
                case 2:
                    $upgrade = "現在耐久エンチャントはRank.2です\n\n強化効果 : 耐久35から耐久50に強化し更に修繕を付与\n\n以下のコストを支払ってMiningToolを強化しますか？";
                    $cost = "コストは" . self::Rank3_MoneyCost . "円と\nMiningToolsEnchantCostItem " . self::Rank3_ItemCost . "個のアイテム\nをインベントリに保持している必要があります";
                    break;
                case 3:
                    $upgrade = "最上位ランクの為アップグレードに対応していません";
                    break;
                default:
                    $upgrade = "Errorが発生しました";
                    Server::getInstance()->getLogger()->error("[" . $player->getName() . "]" . __DIR__ . "ディレクトリに存在する" . __CLASS__ . "クラスの" . __LINE__ . "行目でエラーが発生しました");
                    break;
            }
        } else {
            $upgrade = "現在、耐久強化はされていません\n\n強化効果 : 耐久10から耐久25に強化\n\n以下のコストを支払ってMiningToolを強化しますか？";
            $cost = "コストは" . self::Rank1_MoneyCost . "円と\nMiningToolsEnchantCostItem " . self::Rank1_ItemCost . "個のアイテム\nをインベントリに保持している必要があります";
        }
        $this
            ->setTitle("Expansion Mining Tools")
            ->setText("{$upgrade}\n\n{$cost}")
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