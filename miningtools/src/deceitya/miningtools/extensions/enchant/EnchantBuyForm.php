<?php

namespace deceitya\miningtools\extensions\enchant;

use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\form\CustomForm;
use deceitya\miningtools\extensions\range\CheckBuyItemCost;
use deceitya\miningtools\extensions\range\SetLoreJudgment;
use onebone\economyapi\EconomyAPI;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\ItemFactory;
use pocketmine\player\Player;
use pocketmine\Server;

class EnchantBuyForm extends CustomForm {

    public function __construct(Player $player, int $level) {
        switch ($level) {
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

    public function handleSubmit(Player $player): void {
        foreach ($player->getInventory()->getItemInHand()->getEnchantments() as $enchant) {
            $enchantName = $enchant->getType()->getName();
            if ($enchantName === VanillaEnchantments::UNBREAKING()->getName()) {
                switch ($enchant->getLevel()) {
                    case 10:
                        $moneyCost = EnchantConfirmForm::Rank_1_MoneyCost;
                        $itemCost = EnchantConfirmForm::Rank_1_ItemCost;
                        $enchantLevel = 25;
                        break;
                    case 25:
                        $moneyCost = EnchantConfirmForm::Rank_2_MoneyCost;
                        $itemCost = EnchantConfirmForm::Rank_2_ItemCost;
                        $enchantLevel = 35;
                        break;
                    case 35:
                        $moneyCost = EnchantConfirmForm::Rank_3_MoneyCost;
                        $itemCost = EnchantConfirmForm::Rank_3_ItemCost;
                        $enchantLevel = "mending";
                        break;
                    default:
                        Server::getInstance()->broadcastMessage("[" . $player->getName() . "]" . __DIR__ . "の" . __LINE__ . "行目でエラーが発生しました");
                        return;
                }
            }
            if (empty($itemCost) || empty($moneyCost) || empty($enchantLevel)) {
                Server::getInstance()->broadcastMessage("[" . $player->getName() . "]" . __DIR__ . "の" . __LINE__ . "行目でエラーが発生しました");
                return;
            }
            if ((new CheckBuyItemCost())->MoneyCheck($player, $moneyCost) === false) return;
            if ((new CheckBuyItemCost())->CostItemCheck($player, $itemCost, EnchantConfirmForm::CostItemId, EnchantConfirmForm::CostItemNBT)) return;
            EconomyAPI::getInstance()->reduceMoney($player, $moneyCost);
            $item = ItemFactory::getInstance()->get(EnchantConfirmForm::CostItemId);
            $player->getInventory()->removeItem($item->setCount($itemCost));
            $inventory = $player->getInventory();
            $inventory->removeItem($item);
            $item->removeEnchantment($enchant->getType());
            if ($enchantLevel === "mending") {
                $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::MENDING(), 1));
                $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 50));
            } else {
                /**
                 * @var Int $enchantLevel ;
                 */
                $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), $enchantLevel));
            }
            $item->setLore((new SetLoreJudgment())->SetLoreJudgment($player, $item));
            $inventory->addItem($item);
            $player->sendMessage("アップグレーどに成功しました");
        }
    }

}