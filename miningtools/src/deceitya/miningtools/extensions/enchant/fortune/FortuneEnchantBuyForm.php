<?php

namespace deceitya\miningtools\extensions\enchant\fortune;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\form\SimpleForm;
use deceitya\miningtools\extensions\CheckPlayerData;
use deceitya\miningtools\extensions\enchant\EnchantFunctionSelectForm;
use deceitya\miningtools\extensions\SetLoreJudgment;
use deceitya\miningtools\Main;
use onebone\economyapi\EconomyAPI;
use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\data\bedrock\EnchantmentIds;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\player\Player;
use pocketmine\Server;

class FortuneEnchantBuyForm extends SimpleForm {

    private array $nbt;

    public function __construct(Player $player, array $nbt) {
        $this->nbt = $nbt;
        $namedTag = $player->getInventory()->getItemInHand()->getNamedTag();
        if ($namedTag->getTag('MiningTools_Expansion_FortuneEnchant') !== null) {
            $upgrade = match ($namedTag->getInt("MiningTools_Expansion_FortuneEnchant")) {
                1 => "上位ツールにアップグレードしますか？\n\n費用は600万円\n範囲は7x7になります",
                2 => "最上位ツールにアップグレードしますか？\n\n費用は1500万円\n範囲は9x9になります",
            };
        } else {
            $upgrade = "上位ツールにアップグレードしますか？\n\n費用は350万円\n範囲は5x5になります";
        }
        $this
            ->setTitle("Mining Tools")
            ->setText($upgrade)
            ->addElements(new Button("アップグレード"));
    }

    public function handleSubmit(Player $player): void {
        $namedTag = $player->getInventory()->getItemInHand()->getNamedTag();
        $rank = null;
        $item = $player->getInventory()->getItemInHand();
        $costItemId = EnchantFunctionSelectForm::CostItemId;
        $costItemNBT = EnchantFunctionSelectForm::CostItemNBT;
        if ((new CheckPlayerData())->checkMiningToolsNBT($player) === false) return;
        if (empty($this->nbt)) {
            if ($item->getNamedTag()->getTag('MiningTools_Expansion_FortuneEnchant') !== null) {
                $player->sendMessage(Main::PrefixRed . "現在所持しているアイテムは最初に持っているアイテムと異なる恐れがあるため不正防止の観点から処理が中断されました");
                return;
            }
            $rank = 1;
            $price = FortuneEnchantConfirmForm::Rank1_MoneyCost;
            $costItem = FortuneEnchantConfirmForm::Rank1_ItemCost;
            if ((new CheckPlayerData())->CheckReduceMoney($player, $price) === false) return;
            if ((new CheckPlayerData())->CheckReduceCostItem($player, $costItem, $costItemId, $costItemNBT) === false) return;
            if ((new CheckPlayerData())->ReduceCostItem($player, $costItem, $costItemId, $costItemNBT) === false) return;
            EconomyAPI::getInstance()->reduceMoney($player, $price);
            $item->removeEnchantment(VanillaEnchantments::SILK_TOUCH());
            $item->addEnchantment(new EnchantmentInstance(EnchantmentIdMap::getInstance()->fromId(EnchantmentIds::FORTUNE), 1));
        }
        if (array_key_exists("MiningTools_Expansion_FortuneEnchant", $this->nbt)) {
            if ($item->hasEnchantment(VanillaEnchantments::SILK_TOUCH())) {
                $item->removeEnchantment(VanillaEnchantments::SILK_TOUCH());
                $player->sendMessage(Main::PrefixRed . '現在所持しているアイテムはシルクタッチエンチャントが付与されているため不正防止やエラー対策の観点からエンチャントが削除されました');
                //エンチャントだけ消してそのまま処理続行
            }
            if ($this->nbt["MiningTools_Expansion_FortuneEnchant"] !== $namedTag->getInt("MiningTools_Expansion_FortuneEnchant")) {
                $player->sendMessage(Main::PrefixRed . "現在所持しているアイテムは最初に持っているアイテムと異なる恐れがあるため不正防止の観点から処理が中断されました");
                return;
            }
            if ($item->getNamedTag()->getTag('MiningTools_Expansion_FortuneEnchant') !== null) {
                switch ($namedTag->getInt("MiningTools_Expansion_FortuneEnchant")) {
                    case 1:
                        $rank = 2;
                        $price = FortuneEnchantConfirmForm::Rank2_MoneyCost;
                        $costItem = FortuneEnchantConfirmForm::Rank2_ItemCost;
                        if ((new CheckPlayerData())->CheckReduceMoney($player, $price) === false) return;
                        if ((new CheckPlayerData())->CheckReduceCostItem($player, $costItem, $costItemId, $costItemNBT) === false) return;
                        if ((new CheckPlayerData())->ReduceCostItem($player, $costItem, $costItemId, $costItemNBT) === false) return;
                        EconomyAPI::getInstance()->reduceMoney($player, $price);
                        $item->addEnchantment(new EnchantmentInstance(EnchantmentIdMap::getInstance()->fromId(EnchantmentIds::FORTUNE), 2));
                        break;
                    case 2:
                        $rank = 3;
                        $price = FortuneEnchantConfirmForm::Rank3_MoneyCost;
                        $costItem = FortuneEnchantConfirmForm::Rank3_ItemCost;
                        if ((new CheckPlayerData())->CheckReduceMoney($player, $price) === false) return;
                        if ((new CheckPlayerData())->CheckReduceCostItem($player, $costItem, $costItemId, $costItemNBT) === false) return;
                        if ((new CheckPlayerData())->ReduceCostItem($player, $costItem, $costItemId, $costItemNBT) === false) return;
                        EconomyAPI::getInstance()->reduceMoney($player, $price);
                        $item->addEnchantment(new EnchantmentInstance(EnchantmentIdMap::getInstance()->fromId(EnchantmentIds::FORTUNE), 3));
                        break;
                    default:
                        Server::getInstance()->getLogger()->error("[" . $player->getName() . "]" . __DIR__ . "ディレクトリに存在する" . __CLASS__ . "クラスの" . __LINE__ . "行目でエラーが発生しました");
                        return;
                }
            }
        }
        if (is_null($rank)) {
            Server::getInstance()->getLogger()->error("[" . $player->getName() . "]" . __DIR__ . "ディレクトリに存在する" . __CLASS__ . "クラスの" . __LINE__ . "行目でエラーが発生しました");
            return;
        }
        $nbt = $item->getNamedTag();
        $nbt->removeTag('MiningTools_Expansion_FortuneEnchant');
        $nbt->setInt('MiningTools_Expansion_FortuneEnchant', $rank);
        $item->setNamedTag($nbt);
        $item->setLore((new SetLoreJudgment())->SetLoreJudgment($player, $item));
        $player->getInventory()->setItemInHand($item);
        Server::getInstance()->broadcastMessage(Main::PrefixYellow . "{$player->getName()}がNetheriteMiningToolsをEx.Rank{$rank}にアップグレードしました");
    }
}