<?php

namespace deceitya\miningtools\extensions\enchant;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\form\SimpleForm;
use deceitya\miningtools\extensions\CheckPlayerData;
use deceitya\miningtools\extensions\SetLoreJudgment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\player\Player;
use pocketmine\Server;

class EnchantBuyForm extends SimpleForm {

    private array $nbt;

    public function __construct(Player $player, array $nbt) {
        $this->nbt = $nbt;
        $namedTag = $player->getInventory()->getItemInHand()->getNamedTag();
        if ($namedTag->getTag('MiningTools_Expansion_Enchant') !== null) {
            $upgrade = match ($namedTag->getInt("MiningTools_Expansion")) {
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
        if (empty($this->nbt)) {
            if ($item->getNamedTag()->getTag('MiningTools_Expansion_Enchant') !== null) {
                $player->sendMessage("現在所持しているアイテムは最初に持っているアイテムでは無いため不正防止の為処理が中断されました");
                return;
            }
            if ($item->getNamedTag()->getTag('MiningTools_3') !== null) {
                $rank = 1;
                if ((new CheckPlayerData())->ReduceMoney($player, EnchantConfirmForm::Rank1_MoneyCost) === false) return;
                if ((new CheckPlayerData())->ReduceCostItem($player, $rank, EnchantConfirmForm::CostItemId, EnchantConfirmForm::CostItemNBT) === false) return;
                $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 25));
            }
        }
        if (array_key_exists("MiningTools_Expansion_Enchant", $this->nbt)) {
            if ($this->nbt["MiningTools_Expansion_Enchant"] !== $namedTag->getInt("MiningTools_Expansion_Enchant")) {
                $player->sendMessage("現在所持しているアイテムは最初に持っているアイテムでは無いため不正防止の為処理が中断されました");
                return;
            }
            if ($item->getNamedTag()->getTag('MiningTools_Expansion_Enchant') !== null) {
                switch ($namedTag->getInt("MiningTools_Expansion_Enchant")) {
                    case 1:
                        $rank = 2;
                        if ((new CheckPlayerData())->ReduceMoney($player, EnchantConfirmForm::Rank2_MoneyCost) === false) return;
                        if ((new CheckPlayerData())->ReduceCostItem($player, $rank, EnchantConfirmForm::CostItemId, EnchantConfirmForm::CostItemNBT) === false) return;
                        $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 35));
                        break;
                    case 2:
                        $rank = 3;
                        if ((new CheckPlayerData())->ReduceMoney($player, EnchantConfirmForm::Rank3_MoneyCost) === false) return;
                        if ((new CheckPlayerData())->ReduceCostItem($player, $rank, EnchantConfirmForm::CostItemId, EnchantConfirmForm::CostItemNBT) === false) return;
                        $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::MENDING(), 1));
                        $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 50));
                        break;
                    default:
                        Server::getInstance()->broadcastMessage("[" . $player->getName() . "]" . __DIR__ . "ディレクトリに存在する" . __CLASS__ . "クラスの" . __LINE__ . "行目でエラーが発生しました");
                        return;
                }
            }
        }
        if (is_null($rank)) {
            Server::getInstance()->broadcastMessage("[" . $player->getName() . "]" . __DIR__ . "ディレクトリに存在する" . __CLASS__ . "クラスの" . __LINE__ . "行目でエラーが発生しました");
            return;
        }
        $nbt = $item->getNamedTag();
        $nbt->removeTag('MiningTools_Expansion_Enchant');
        $nbt->setInt('MiningTools_Expansion_Enchant', $rank);
        $item->setNamedTag($nbt);
        $item->setLore((new SetLoreJudgment())->SetLoreJudgment($item));
        $player->getInventory()->setItemInHand($item);
        Server::getInstance()->broadcastMessage("§bMiningTools §7>> §e{$player->getName()}がNetheriteMiningToolsをEx.Rank{$rank}にアップグレードしました");
    }
}