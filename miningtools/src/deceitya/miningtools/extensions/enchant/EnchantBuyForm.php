<?php

namespace deceitya\miningtools\extensions\enchant;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\form\SimpleForm;
use deceitya\miningtools\extensions\SetLoreJudgment;
use onebone\economyapi\EconomyAPI;
use pocketmine\player\Player;
use pocketmine\Server;

class EnchantBuyForm extends SimpleForm {

    private array $nbt;

    public function __construct(Player $player, array $nbt) {
        $this->nbt = $nbt;
        $upgrade = "未定義のエラー";
        $namedTag = $player->getInventory()->getItemInHand()->getNamedTag();
        if ($namedTag->getTag('MiningTools_Expansion_Range') !== null) {
            $upgrade = match ($namedTag->getInt("MiningTools_Expansion")) {
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
        $item = $player->getInventory()->getItemInHand();
        if (array_key_exists("MiningTools_Expansion_Range", $this->nbt)) {
            if ($this->nbt["MiningTools_Expansion_Range"] !== $namedTag->getInt("MiningTools_Expansion_Range")) {
                $player->sendMessage("現在所持しているアイテムは最初に持っているアイテムでは無いため不正防止の為処理が中断されました");
                return;
            }
            if ($item->getNamedTag()->getTag('MiningTools_Expansion_Range') !== null) {
                $nbt = $item->getNamedTag();
                switch ($namedTag->getInt("MiningTools_Expansion_Range")) {
                    case 1:
                        $price = 6000000;
                        $radius = 2;
                        $this->onReduceMoney($player, $price);
                        break;
                    case 2:
                        $price = 15000000;
                        $radius = 3;
                        $this->onReduceMoney($player, $price);
                        break;
                    default:
                        Server::getInstance()->broadcastMessage("[" . $player->getName() . "]" . __DIR__ . "の" . __LINE__ . "行目でエラーが発生しました");
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
                $player->sendMessage("現在所持しているアイテムは最初に持っているアイテムでは無いため不正防止の為処理が中断されました");
                return;
            }
            if ($item->getNamedTag()->getTag('MiningTools_3') !== null) {
                $radius = 1;
                $price = 3500000;
                if (!$this->onReduceMoney($player, $price)) return;
                $nbt = $item->getNamedTag();
                $tag = "MiningTools_3";
                $nbt->removeTag($tag);
                $nbt->setInt('MiningTools_Expansion_Range', $radius);
                $item->setNamedTag($nbt);
                $item->setCustomName("§aNetheriteMiningPickaxe Ex.Primary");
                $player->getInventory()->setItemInHand($item);
            }
        }
        $item->setLore((new SetLoreJudgment())->SetLoreJudgment($item));
        $player->getInventory()->setItemInHand($item);
        Server::getInstance()->broadcastMessage("§bMiningTools §7>> §e{$player->getName()}がNetheriteMiningToolsをEx.Rank{$radius}にアップグレードしました");
    }

    public function onReduceMoney(Player $player, $price): bool {
        if (EconomyAPI::getInstance()->myMoney($player) <= $price) {
            $player->sendMessage('§bMiningTools §7>> §cお金が足りません');
            return false;
        }
        EconomyAPI::getInstance()->reduceMoney($player, $price);
        return true;
    }
}

//    public function __construct(Player $player, int $level) {
//        switch ($level) {
//            case 10:
//                $this->addElements(
//                    new Label("現在、耐久力エンチャントはされていません\n以下のコストを支払ってMiningToolを強化しますか？"),
//                    new Label("コスト\n")
//                );
//                return;
//            case 25:
//                $this->addElements(
//                    new Label("現在、耐久力エンチャントはRank.2です\n以下のコストを支払ってMiningToolを強化しますか？"),
//                    new Label("コスト\n")
//                );
//                return;
//            case 35:
//                $this->addElements(
//                    new Label("現在、耐久力エンチャントは最大レベルです\nエンドコンテンツとして修繕を付与することが可能です"),
//                    new Label("コスト\n")
//                );
//                return;
//            default:
//                Server::getInstance()->broadcastMessage("[" . $player->getName() . "]" . __DIR__ . "の" . __LINE__ . "行目でエラーが発生しました");
//                $this->addElement(new Label("例外が発生しました"));
//        }
//    }
//
//    public function handleSubmit(Player $player): void {
//        foreach ($player->getInventory()->getItemInHand()->getEnchantments() as $enchant) {
//            $enchantName = $enchant->getType()->getName();
//            if ($enchantName === VanillaEnchantments::UNBREAKING()->getName()) {
//                switch ($enchant->getLevel()) {
//                    case 10:
//                        $moneyCost = EnchantConfirmForm::Rank_1_MoneyCost;
//                        $itemCost = EnchantConfirmForm::Rank_1_ItemCost;
//                        $enchantLevel = 25;
//                        $nbtInt = 1;
//                        break;
//                    case 25:
//                        $moneyCost = EnchantConfirmForm::Rank_2_MoneyCost;
//                        $itemCost = EnchantConfirmForm::Rank_2_ItemCost;
//                        $enchantLevel = 35;
//                        $nbtInt = 2;
//                        break;
//                    case 35:
//                        $moneyCost = EnchantConfirmForm::Rank_3_MoneyCost;
//                        $itemCost = EnchantConfirmForm::Rank_3_ItemCost;
//                        $enchantLevel = "mending";
//                        $nbtInt = 3;
//                        break;
//                    default:
//                        Server::getInstance()->broadcastMessage("[" . $player->getName() . "]" . __DIR__ . "の" . __LINE__ . "行目でエラーが発生しました");
//                        return;
//                }
//            }
//        }
//        if (empty($itemCost) || empty($moneyCost) || empty($enchantLevel) || empty($nbtInt)) {
//            Server::getInstance()->broadcastMessage("[" . $player->getName() . "]" . __DIR__ . "の" . __LINE__ . "行目でエラーが発生しました");
//            return;
//        }
//        if ((new CheckPlayerData())->MoneyCheck($player, $moneyCost) === false) return;
//        if ((new CheckPlayerData())->CostItemCheck($player, $itemCost, EnchantConfirmForm::CostItemId, EnchantConfirmForm::CostItemNBT) === false) return;
//        EconomyAPI::getInstance()->reduceMoney($player, $moneyCost);
//        $costItem = ItemFactory::getInstance()->get(EnchantConfirmForm::CostItemId);
//        $player->getInventory()->removeItem($costItem->setCount($itemCost));
//        $inventory = $player->getInventory();
//        $inventory->removeItem($costItem);
//        if (empty($enchant)) {
//            Server::getInstance()->broadcastMessage("[" . $player->getName() . "]" . __DIR__ . "の" . __LINE__ . "行目でエラーが発生しました");
//            return;
//        }
//        $item = $inventory->getItemInHand();
//        $item->removeEnchantment($enchant->getType());
//        if ($enchantLevel === "mending") {
//            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::MENDING(), 1));
//            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 50));
//        } else {
//            /** @var Int $enchantLevel */
//            $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), $enchantLevel));
//        }
//        $item->getNamedTag()->setInt('MiningTools_Expansion_Enchant', $nbtInt);
//        $item->setLore((new SetLoreJudgment())->SetLoreJudgment($inventory->getItemInHand()));
//        $inventory->setItemInHand($item);
//        $player->sendMessage("アップグレーどに成功しました");
//    }
//
//}