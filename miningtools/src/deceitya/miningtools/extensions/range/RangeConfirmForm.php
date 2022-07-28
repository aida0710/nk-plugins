<?php

namespace deceitya\miningtools\extensions\range;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\form\SimpleForm;
use pocketmine\player\Player;
use pocketmine\Server;

class RangeConfirmForm extends SimpleForm {

    private array $nbt = [];

    public const Rank1_MoneyCost = 800000;
    public const Rank2_MoneyCost = 6000000;
    public const Rank3_MoneyCost = 25000000;

    public const Rank1_ItemCost = 1;
    public const Rank2_ItemCost = 15;
    public const Rank3_ItemCost = 20;

    public const CostItemId = -302;
    public const CostItemNBT = "MiningToolsRangeCostItem";

    public function __construct(Player $player) {
        $cost = "";
        $namedTag = $player->getInventory()->getItemInHand()->getNamedTag();
        if ($namedTag->getTag('MiningTools_Expansion_Range') !== null) {
            $this->nbt = ["MiningTools_Expansion_Range" => $namedTag->getInt("MiningTools_Expansion_Range")];
            switch ($namedTag->getInt('MiningTools_Expansion_Range')) {
                case 1:
                    $upgrade = "現在、範囲強化はRank.1[5x5]です\n\n強化効果 : 破壊範囲[5x5]->[7x7]\n\n以下のコストを支払ってMiningToolを強化しますか？\n\n";
                    $cost = "コストは" . self::Rank2_MoneyCost . "円と\nMiningToolsEnchantCostItem " . self::Rank2_ItemCost . "個のアイテム\nをインベントリに保持している必要があります";
                    break;
                case 2:
                    $upgrade = "現在、範囲強化はRank.2[7x7]です\n\n強化効果 : 破壊範囲[7x7]->[9x9]\n\n以下のコストを支払ってMiningToolを強化しますか？\n\n";
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
        } elseif ($namedTag->getTag('MiningTools_3') !== null) {
            $this->nbt = ["MiningTools_3" => $namedTag->getInt("MiningTools_3")];
            $upgrade = "現在、範囲強化はされていません\n\n強化効果 : 破壊範囲[3x3]->[5x5]\n\n以下のコストを支払ってMiningToolを強化しますか？";
            $cost = "コストは" . self::Rank1_MoneyCost . "円と\nMiningToolsEnchantCostItem " . self::Rank1_ItemCost . "個のアイテム\nをインベントリに保持している必要があります";
        } else {
            $upgrade = "Errorが発生しました";
            Server::getInstance()->getLogger()->error("[" . $player->getName() . "]" . __DIR__ . "ディレクトリに存在する" . __CLASS__ . "クラスの" . __LINE__ . "行目でエラーが発生しました");
        }
        $this
            ->setTitle("Expansion Mining Tools")
            ->setText("{$upgrade}\n\n{$cost}")
            ->addElements(new Button("アップデートする"));
    }

    public function handleSubmit(Player $player): void {
        if (empty($this->nbt)) {
            Server::getInstance()->getLogger()->error("[" . $player->getName() . "]" . __DIR__ . "ディレクトリに存在する" . __CLASS__ . "クラスの" . __LINE__ . "行目でエラーが発生しました");
            return;
        }
        if ($player->getInventory()->getItemInHand()->getNamedTag()->getTag('MiningTools_Expansion_Range') !== null) {
            if ($player->getInventory()->getItemInHand()->getNamedTag()->getInt('MiningTools_Expansion_Range') === 3) {
                return;
            }
        }
        $player->sendForm(new RangeBuyForm($player, $this->nbt));
    }
}
