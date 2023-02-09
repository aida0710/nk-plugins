<?php

declare(strict_types = 1);
namespace deceitya\miningtools\extensions\enchant\unbreaking;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\form\SimpleForm;
use deceitya\miningtools\extensions\CheckPlayerData;
use deceitya\miningtools\extensions\enchant\EnchantFunctionSelectForm;
use deceitya\miningtools\extensions\SetLoreJudgment;
use Error;
use lazyperson710\core\packet\SendMessage\SendBroadcastMessage;
use lazyperson710\core\packet\SendMessage\SendMessage;
use onebone\economyapi\EconomyAPI;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\player\Player;
use function array_key_exists;
use function is_null;

class UnbreakingEnchantBuyForm extends SimpleForm {

	private array $nbt;

	public function __construct(Player $player, array $nbt) {
		$this->nbt = $nbt;
		$namedTag = $player->getInventory()->getItemInHand()->getNamedTag();
		if ($namedTag->getTag('MiningTools_Expansion_UnbreakingEnchant') !== null) {
			$upgrade = match ($namedTag->getInt('MiningTools_Expansion_UnbreakingEnchant')) {
				1 => '現在の所持金 : ' . EconomyAPI::getInstance()->myMoney($player) . "\n\n強化効果 : 耐久25から耐久35に強化\n\nコストは" . UnbreakingEnchantConfirmForm::Rank2_MoneyCost . "円と\nMiningToolsEnchantCostItem " . UnbreakingEnchantConfirmForm::Rank2_ItemCost . "個のアイテム\nをインベントリに保持している必要があります",
				2 => '現在の所持金 : ' . EconomyAPI::getInstance()->myMoney($player) . "\n\n強化効果 : 耐久35から耐久50に強化し更に修繕を付与\n\nコストは" . UnbreakingEnchantConfirmForm::Rank3_MoneyCost . "円と\nMiningToolsEnchantCostItem " . UnbreakingEnchantConfirmForm::Rank3_ItemCost . "個のアイテム\nをインベントリに保持している必要があります",
			};
		} else {
			$upgrade = '現在の所持金 : ' . EconomyAPI::getInstance()->myMoney($player) . "\n\n強化効果 : 耐久10から耐久25に強化\n\nコストは" . UnbreakingEnchantConfirmForm::Rank1_MoneyCost . "円と\nMiningToolsEnchantCostItem " . UnbreakingEnchantConfirmForm::Rank1_ItemCost . "個のアイテム\nをインベントリに保持している必要があります";
		}
		$this
			->setTitle('Expansion Mining Tools')
			->setText($upgrade)
			->addElements(new Button('アップグレード'));
	}

	public function handleSubmit(Player $player) : void {
		$namedTag = $player->getInventory()->getItemInHand()->getNamedTag();
		$rank = null;
		$item = $player->getInventory()->getItemInHand();
		$costItemId = EnchantFunctionSelectForm::CostItemId;
		$costItemNBT = EnchantFunctionSelectForm::CostItemNBT;
		if ((new CheckPlayerData())->checkMiningToolsNBT($player) === false) return;
		if (empty($this->nbt)) {
			if ($item->getNamedTag()->getTag('MiningTools_Expansion_UnbreakingEnchant') !== null) {
				SendMessage::Send($player, '現在所持しているアイテムは最初に持っているアイテムと異なる恐れがあるため不正防止の観点から処理が中断されました', 'MiningTools', false);
				return;
			}
			$rank = 1;
			$price = UnbreakingEnchantConfirmForm::Rank1_MoneyCost;
			$costItem = UnbreakingEnchantConfirmForm::Rank1_ItemCost;
			if ((new CheckPlayerData())->CheckReduceMoney($player, $price) === false) return;
			if ((new CheckPlayerData())->CheckAndReduceCostItem($player, $costItem, $costItemId, $costItemNBT) === false) return;
			EconomyAPI::getInstance()->reduceMoney($player, $price);
			$item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 25));
		}
		if (array_key_exists('MiningTools_Expansion_UnbreakingEnchant', $this->nbt)) {
			if ($this->nbt['MiningTools_Expansion_UnbreakingEnchant'] !== $namedTag->getInt('MiningTools_Expansion_UnbreakingEnchant')) {
				SendMessage::Send($player, '現在所持しているアイテムは最初に持っているアイテムと異なる恐れがあるため不正防止の観点から処理が中断されました', 'MiningTools', false);
				return;
			}
			if ($item->getNamedTag()->getTag('MiningTools_Expansion_UnbreakingEnchant') !== null) {
				switch ($namedTag->getInt('MiningTools_Expansion_UnbreakingEnchant')) {
					case 1:
						$rank = 2;
						$price = UnbreakingEnchantConfirmForm::Rank2_MoneyCost;
						$costItem = UnbreakingEnchantConfirmForm::Rank2_ItemCost;
						if ((new CheckPlayerData())->CheckReduceMoney($player, $price) === false) return;
						if ((new CheckPlayerData())->CheckAndReduceCostItem($player, $costItem, $costItemId, $costItemNBT) === false) return;
						EconomyAPI::getInstance()->reduceMoney($player, $price);
						$item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 35));
						break;
					case 2:
						$rank = 3;
						$price = UnbreakingEnchantConfirmForm::Rank3_MoneyCost;
						$costItem = UnbreakingEnchantConfirmForm::Rank3_ItemCost;
						if ((new CheckPlayerData())->CheckReduceMoney($player, $price) === false) return;
						if ((new CheckPlayerData())->CheckAndReduceCostItem($player, $costItem, $costItemId, $costItemNBT) === false) return;
						EconomyAPI::getInstance()->reduceMoney($player, $price);
						$item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::MENDING(), 1));
						$item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), 50));
						break;
					default:
						throw new Error('rank3以上の値が入力されました');
				}
			}
		}
		if (is_null($rank)) {
			throw new Error('rankがnullの為不明な挙動として処理しました');
		}
		$nbt = $item->getNamedTag();
		$nbt->removeTag('MiningTools_Expansion_UnbreakingEnchant');
		$nbt->setInt('MiningTools_Expansion_UnbreakingEnchant', $rank);
		$item->setNamedTag($nbt);
		$item->setLore((new SetLoreJudgment())->SetLoreJudgment($player, $item));
		$player->getInventory()->setItemInHand($item);
		SendBroadcastMessage::Send("{$player->getName()}がMiningToolsを耐久エンチャント強化 - Rank{$rank}にアップグレードしました", 'MiningTools');
	}
}
