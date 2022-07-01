<?php

namespace deceitya\miningtools\extensions\range;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\form\SimpleForm;
use deceitya\miningtools\extensions\CheckPlayerData;
use deceitya\miningtools\extensions\SetLoreJudgment;
use deceitya\miningtools\Main;
use onebone\economyapi\EconomyAPI;
use pocketmine\player\Player;
use pocketmine\Server;

class RangeBuyForm extends SimpleForm {

    private array $nbt;

    public function __construct(Player $player, array $nbt) {
        $this->nbt = $nbt;
        $upgrade = "未定義のエラー";
        $namedTag = $player->getInventory()->getItemInHand()->getNamedTag();
        if ($namedTag->getTag('MiningTools_Expansion_Range') !== null) {
            $upgrade = match ($namedTag->getInt("MiningTools_Expansion_Range")) {
                1 => "上位ツールにアップグレードしますか？\n\n費用は600万円\n範囲は7x7になります",
                2 => "最上位ツールにアップグレードしますか？\n\n費用は1500万円\n範囲は9x9になります",
            };
        } elseif ($namedTag->getTag('MiningTools_3') !== null) {
            $upgrade = "上位ツールにアップグレードしますか？\n\n費用は350万円\n範囲は5x5になります";
        }
        $this
            ->setTitle("Mining Tools")
            ->setText($upgrade)
            ->addElements(new Button("アップグレード"));
    }

    public function handleSubmit(Player $player): void {
        $namedTag = $player->getInventory()->getItemInHand()->getNamedTag();
        $radius = 0;
        $costItemId = RangeConfirmForm::CostItemId;
        $costItemNBT = RangeConfirmForm::CostItemNBT;
        $item = $player->getInventory()->getItemInHand();
        if ((new CheckPlayerData())->checkMiningToolsNBT($player) === false) return;
        if (array_key_exists("MiningTools_Expansion_Range", $this->nbt)) {
            if ($this->nbt["MiningTools_Expansion_Range"] !== $namedTag->getInt("MiningTools_Expansion_Range")) {
                $player->sendMessage(Main::PrefixRed . "現在所持しているアイテムは最初に持っているアイテムと異なる恐れがあるため不正防止の観点から処理が中断されました");
                return;
            }
            if ($item->getNamedTag()->getTag('MiningTools_Expansion_Range') !== null) {
                $nbt = $item->getNamedTag();
                switch ($namedTag->getInt("MiningTools_Expansion_Range")) {
                    case 1:
                        $radius = 2;
                        $price = RangeConfirmForm::Rank2_MoneyCost;
                        $costItem = RangeConfirmForm::Rank2_ItemCost;
                        if ((new CheckPlayerData())->CheckReduceMoney($player, $price) === false) return;
                        if ((new CheckPlayerData())->CheckReduceCostItem($player, $costItem, $costItemId, $costItemNBT) === false) return;
                        if ((new CheckPlayerData())->ReduceCostItem($player, $costItem, $costItemId, $costItemNBT) === false) return;
                        EconomyAPI::getInstance()->reduceMoney($player, $price);
                        break;
                    case 2:
                        $radius = 3;
                        $price = RangeConfirmForm::Rank3_MoneyCost;
                        $costItem = RangeConfirmForm::Rank3_ItemCost;
                        if ((new CheckPlayerData())->CheckReduceMoney($player, $price) === false) return;
                        if ((new CheckPlayerData())->CheckReduceCostItem($player, $costItem, $costItemId, $costItemNBT) === false) return;
                        if ((new CheckPlayerData())->ReduceCostItem($player, $costItem, $costItemId, $costItemNBT) === false) return;
                        EconomyAPI::getInstance()->reduceMoney($player, $price);
                        break;
                    default:
                        Server::getInstance()->getLogger()->error("[" . $player->getName() . "]" . __DIR__ . "ディレクトリに存在する" . __CLASS__ . "クラスの" . __LINE__ . "行目でエラーが発生しました");
                        return;
                }
                $tag = "MiningTools_Expansion_Range";
                $nbt->removeTag($tag);
                $nbt->setInt('MiningTools_Expansion_Range', $radius);
                $item->setNamedTag($nbt);
                $itemName = match ($namedTag->getInt("MiningTools_Expansion_Range")) {
                    1 => "§aNetheriteMiningPickaxe Ex.Secondary",
                    2 => "§aNetheriteMiningPickaxe Ex.Tertiary",
                };
                $item->setCustomName($itemName);
                $player->getInventory()->setItemInHand($item);
            }
        }
        if (array_key_exists("MiningTools_3", $this->nbt)) {
            if ($this->nbt["MiningTools_3"] !== $namedTag->getInt("MiningTools_3")) {
                $player->sendMessage(Main::PrefixRed . "現在所持しているアイテムは最初に持っているアイテムと異なる恐れがあるため不正防止の観点から処理が中断されました");
                return;
            }
            if ($item->getNamedTag()->getTag('MiningTools_3') !== null) {
                $radius = 1;
                $price = RangeConfirmForm::Rank1_MoneyCost;
                $costItem = RangeConfirmForm::Rank1_ItemCost;
                if ((new CheckPlayerData())->CheckReduceMoney($player, $price) === false) return;
                if ((new CheckPlayerData())->CheckReduceCostItem($player, $costItem, $costItemId, $costItemNBT) === false) return;
                if ((new CheckPlayerData())->ReduceCostItem($player, $costItem, $costItemId, $costItemNBT) === false) return;
                EconomyAPI::getInstance()->reduceMoney($player, $price);
                $nbt = $item->getNamedTag();
                $tag = "MiningTools_3";
                $nbt->removeTag($tag);
                $nbt->setInt('MiningTools_Expansion_Range', $radius);
                $item->setNamedTag($nbt);
                $item->setCustomName("§aNetheriteMiningPickaxe Ex.Primary");
                $player->getInventory()->setItemInHand($item);
            }
        }
        $item->setLore((new SetLoreJudgment())->SetLoreJudgment($player, $item));
        $player->getInventory()->setItemInHand($item);
        Server::getInstance()->broadcastMessage("§bMiningTools §7>> §e{$player->getName()}がNetheriteMiningToolsをEx.Rank{$radius}にアップグレードしました");
    }

}
