<?php

declare(strict_types=1);

namespace deceitya\miningtools\extensions\enchant\fortune;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\form\SimpleForm;
use deceitya\miningtools\extensions\CheckPlayerData;
use deceitya\miningtools\extensions\enchant\EnchantFunctionSelectForm;
use deceitya\miningtools\extensions\SetLoreJudgment;
use lazyperson710\core\packet\SendMessage\SendBroadcastMessage;
use lazyperson710\core\packet\SendMessage\SendMessage;
use onebone\economyapi\EconomyAPI;
use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\data\bedrock\EnchantmentIds;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\player\Player;
use function array_key_exists;
use function is_null;

class FortuneEnchantBuyForm extends SimpleForm {

	private array $nbt;

	public function __construct(Player $player, array $nbt) {
		$this->nbt = $nbt;
		$namedTag = $player->getInventory()->getItemInHand()->getNamedTag();
		if ($namedTag->getTag('MiningTools_Expansion_FortuneEnchant') !== null) {
			$upgrade = match ($namedTag->getInt("MiningTools_Expansion_FortuneEnchant")) {
				1 => "現在の所持金 : " . EconomyAPI::getInstance()->myMoney($player) . "\n\n強化効果 : 幸運1から幸運2に強化\n\nコストは" . FortuneEnchantConfirmForm::Rank2_MoneyCost . "円と\nMiningToolsEnchantCostItem " . FortuneEnchantConfirmForm::Rank2_ItemCost . "個のアイテム\nをインベントリに保持している必要があります",
				2 => "現在の所持金 : " . EconomyAPI::getInstance()->myMoney($player) . "\n\n強化効果 : 幸運2から幸運3に強化\n\nコストは" . FortuneEnchantConfirmForm::Rank3_MoneyCost . "円と\nMiningToolsEnchantCostItem " . FortuneEnchantConfirmForm::Rank3_ItemCost . "個のアイテム\nをインベントリに保持している必要があります",
			};
		} else {
			$upgrade = "現在の所持金 : " . EconomyAPI::getInstance()->myMoney($player) . "\n\n強化効果 : シルクタッチを削除し幸運1を付与\n\nコストは" . FortuneEnchantConfirmForm::Rank1_MoneyCost . "円と\nMiningToolsEnchantCostItem " . FortuneEnchantConfirmForm::Rank1_ItemCost . "個のアイテム\nをインベントリに保持している必要があります";
		}
		$this
			->setTitle("Expansion Mining Tools")
			->setText($upgrade)
			->addElements(new Button("アップグレード"));
	}

	public function handleSubmit(Player $player) : void {
		$namedTag = $player->getInventory()->getItemInHand()->getNamedTag();
		$rank = null;
		$item = $player->getInventory()->getItemInHand();
		$costItemId = EnchantFunctionSelectForm::CostItemId;
		$costItemNBT = EnchantFunctionSelectForm::CostItemNBT;
		if ((new CheckPlayerData())->checkMiningToolsNBT($player) === false) return;
		if (empty($this->nbt)) {
			if ($item->getNamedTag()->getTag('MiningTools_Expansion_FortuneEnchant') !== null) {
				SendMessage::Send($player, "現在所持しているアイテムは最初に持っているアイテムと異なる恐れがあるため不正防止の観点から処理が中断されました", "MiningTools", false);
				return;
			}
			$rank = 1;
			$price = FortuneEnchantConfirmForm::Rank1_MoneyCost;
			$costItem = FortuneEnchantConfirmForm::Rank1_ItemCost;
			if ((new CheckPlayerData())->CheckReduceMoney($player, $price) === false) return;
			if ((new CheckPlayerData())->CheckAndReduceCostItem($player, $costItem, $costItemId, $costItemNBT) === false) return;
			EconomyAPI::getInstance()->reduceMoney($player, $price);
			$item->removeEnchantment(VanillaEnchantments::SILK_TOUCH());
			$item->addEnchantment(new EnchantmentInstance(EnchantmentIdMap::getInstance()->fromId(EnchantmentIds::FORTUNE), 1));
		}
		if (array_key_exists("MiningTools_Expansion_FortuneEnchant", $this->nbt)) {
			if ($item->hasEnchantment(VanillaEnchantments::SILK_TOUCH())) {
				$item->removeEnchantment(VanillaEnchantments::SILK_TOUCH());
				SendMessage::Send($player, "現在所持しているアイテムはシルクタッチエンチャントが付与されているため不正防止やエラー対策の観点からエンチャントが削除されました", "MiningTools", false);
				//エンチャントだけ消してそのまま処理続行
			}
			if ($this->nbt["MiningTools_Expansion_FortuneEnchant"] !== $namedTag->getInt("MiningTools_Expansion_FortuneEnchant")) {
				SendMessage::Send($player, "現在所持しているアイテムは最初に持っているアイテムと異なる恐れがあるため不正防止の観点から処理が中断されました", "MiningTools", false);
				return;
			}
			if ($item->getNamedTag()->getTag('MiningTools_Expansion_FortuneEnchant') !== null) {
				switch ($namedTag->getInt("MiningTools_Expansion_FortuneEnchant")) {
					case 1:
						$rank = 2;
						$price = FortuneEnchantConfirmForm::Rank2_MoneyCost;
						$costItem = FortuneEnchantConfirmForm::Rank2_ItemCost;
						if ((new CheckPlayerData())->CheckReduceMoney($player, $price) === false) return;
						if ((new CheckPlayerData())->CheckAndReduceCostItem($player, $costItem, $costItemId, $costItemNBT) === false) return;
						EconomyAPI::getInstance()->reduceMoney($player, $price);
						$item->addEnchantment(new EnchantmentInstance(EnchantmentIdMap::getInstance()->fromId(EnchantmentIds::FORTUNE), 2));
						break;
					case 2:
						$rank = 3;
						$price = FortuneEnchantConfirmForm::Rank3_MoneyCost;
						$costItem = FortuneEnchantConfirmForm::Rank3_ItemCost;
						if ((new CheckPlayerData())->CheckReduceMoney($player, $price) === false) return;
						if ((new CheckPlayerData())->CheckAndReduceCostItem($player, $costItem, $costItemId, $costItemNBT) === false) return;
						EconomyAPI::getInstance()->reduceMoney($player, $price);
						$item->addEnchantment(new EnchantmentInstance(EnchantmentIdMap::getInstance()->fromId(EnchantmentIds::FORTUNE), 3));
						break;
					default:
						throw new \Error("rank3以上の値が入力されました");
				}
			}
		}
		if (is_null($rank)) {
			throw new \Error("rankがnullの為不明な挙動として処理しました");
		}
		$nbt = $item->getNamedTag();
		$nbt->removeTag('MiningTools_Expansion_FortuneEnchant');
		$nbt->setInt('MiningTools_Expansion_FortuneEnchant', $rank);
		$item->setNamedTag($nbt);
		$item->setLore((new SetLoreJudgment())->SetLoreJudgment($player, $item));
		$player->getInventory()->setItemInHand($item);
		SendBroadcastMessage::Send("{$player->getName()}がMiningToolsを幸運エンチャント強化 - Rank{$rank}にアップグレードしました", "MiningTools");
	}
}
