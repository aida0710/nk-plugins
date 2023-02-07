<?php

declare(strict_types=1);
namespace deceitya\miningtools\extensions\range;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\form\SimpleForm;
use deceitya\miningtools\extensions\CheckPlayerData;
use deceitya\miningtools\extensions\enchant\unbreaking\UnbreakingEnchantConfirmForm;
use deceitya\miningtools\extensions\SetLoreJudgment;
use lazyperson710\core\packet\SendMessage\SendBroadcastMessage;
use lazyperson710\core\packet\SendMessage\SendMessage;
use onebone\economyapi\EconomyAPI;
use pocketmine\player\Player;
use function array_key_exists;
use function is_null;

class RangeBuyForm extends SimpleForm {

	private array $nbt;

	public function __construct(Player $player, array $nbt) {
		$this->nbt = $nbt;
		$upgrade = null;
		$namedTag = $player->getInventory()->getItemInHand()->getNamedTag();
		if ($namedTag->getTag('MiningTools_Expansion_Range') !== null) {
			$upgrade = match ($namedTag->getInt("MiningTools_Expansion_Range")) {
				1 => "現在の所持金 : " . EconomyAPI::getInstance()->myMoney($player) . "\n\n強化効果 : 破壊範囲[5x5]->[7x7]\n\nコストは" . UnbreakingEnchantConfirmForm::Rank2_MoneyCost . "円と\nMiningToolsEnchantCostItem " . UnbreakingEnchantConfirmForm::Rank2_ItemCost . "個のアイテム\nをインベントリに保持している必要があります",
				2 => "現在の所持金 : " . EconomyAPI::getInstance()->myMoney($player) . "\n\n強化効果 : 破壊範囲[7x7]->[9x9]\n\nコストは" . UnbreakingEnchantConfirmForm::Rank3_MoneyCost . "円と\nMiningToolsEnchantCostItem " . UnbreakingEnchantConfirmForm::Rank3_ItemCost . "個のアイテム\nをインベントリに保持している必要があります",
			};
		} elseif ($namedTag->getTag('MiningTools_3') !== null) {
			$upgrade = "現在の所持金 : " . EconomyAPI::getInstance()->myMoney($player) . "\n\n強化効果 : 破壊範囲[3x3]->[5x5]\n\nコストは" . UnbreakingEnchantConfirmForm::Rank1_MoneyCost . "円と\nMiningToolsEnchantCostItem " . UnbreakingEnchantConfirmForm::Rank1_ItemCost . "個のアイテム\nをインベントリに保持している必要があります";
		}
		if (is_null($upgrade)) throw new \Error("値が代入されませんでした");
		$this
			->setTitle("Expansion Mining Tools")
			->setText($upgrade)
			->addElements(new Button("アップグレード"));
	}

	public function handleSubmit(Player $player) : void {
		$namedTag = $player->getInventory()->getItemInHand()->getNamedTag();
		$radius = 0;
		$costItemId = RangeConfirmForm::CostItemId;
		$costItemNBT = RangeConfirmForm::CostItemNBT;
		$item = $player->getInventory()->getItemInHand();
		if ((new CheckPlayerData())->checkMiningToolsNBT($player) === false) return;
		if (array_key_exists("MiningTools_Expansion_Range", $this->nbt)) {
			if ($this->nbt["MiningTools_Expansion_Range"] !== $namedTag->getInt("MiningTools_Expansion_Range")) {
				SendMessage::Send($player, "現在所持しているアイテムは最初に持っているアイテムと異なる恐れがあるため不正防止の観点から処理が中断されました", "MiningTools", false);
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
						if ((new CheckPlayerData())->CheckAndReduceCostItem($player, $costItem, $costItemId, $costItemNBT) === false) return;
						EconomyAPI::getInstance()->reduceMoney($player, $price);
						break;
					case 2:
						$radius = 3;
						$price = RangeConfirmForm::Rank3_MoneyCost;
						$costItem = RangeConfirmForm::Rank3_ItemCost;
						if ((new CheckPlayerData())->CheckReduceMoney($player, $price) === false) return;
						if ((new CheckPlayerData())->CheckAndReduceCostItem($player, $costItem, $costItemId, $costItemNBT) === false) return;
						EconomyAPI::getInstance()->reduceMoney($player, $price);
						break;
					default:
						throw new \Error("rank3以上の値が入力されました");
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
				SendMessage::Send($player, "現在所持しているアイテムは最初に持っているアイテムと異なる恐れがあるため不正防止の観点から処理が中断されました", "MiningTools", false);
				return;
			}
			if ($item->getNamedTag()->getTag('MiningTools_3') !== null) {
				$radius = 1;
				$price = RangeConfirmForm::Rank1_MoneyCost;
				$costItem = RangeConfirmForm::Rank1_ItemCost;
				if ((new CheckPlayerData())->CheckReduceMoney($player, $price) === false) return;
				if ((new CheckPlayerData())->CheckAndReduceCostItem($player, $costItem, $costItemId, $costItemNBT) === false) return;
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
		SendBroadcastMessage::Send("{$player->getName()}がMiningToolsを採掘範囲強化 - Rank{$radius}にアップグレードしました", "MiningTools");
	}

}
