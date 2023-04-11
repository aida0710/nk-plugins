<?php

declare(strict_types = 0);

namespace lazyperson0710\miningtools\extensions\range;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\form\SimpleForm;
use Deceitya\MiningLevel\MiningLevelAPI;
use Error;
use lazyperson710\core\packet\SendForm;
use pocketmine\player\Player;

class RangeConfirmForm extends SimpleForm {

	private array $nbt = [];
	private bool $judgement = true;

	public const Rank1_MoneyCost = 800000;
	public const Rank2_MoneyCost = 6000000;
	public const Rank3_MoneyCost = 25000000;

	public const Rank1_ItemCost = 5;
	public const Rank2_ItemCost = 25;
	public const Rank3_ItemCost = 45;

	public const Rank1_MiningLevelLimit = 250;
	public const Rank2_MiningLevelLimit = 450;
	public const Rank3_MiningLevelLimit = 650;

	public const CostItemId = -302;
	public const CostItemNBT = 'MiningToolsRangeCostItem';

	public function __construct(Player $player) {
		$cost = '';
		$namedTag = $player->getInventory()->getItemInHand()->getNamedTag();
		$limit = null;
		if ($namedTag->getTag('MiningTools_Expansion_Range') !== null) {
			$this->nbt = ['MiningTools_Expansion_Range' => $namedTag->getInt('MiningTools_Expansion_Range')];
			switch ($namedTag->getInt('MiningTools_Expansion_Range')) {
				case 1:
					if (MiningLevelAPI::getInstance()->getLevel($player) < self::Rank2_MiningLevelLimit) {
						$limit = "§cレベルが足りない為強化出来ません。\n要求レベル: " . self::Rank2_MiningLevelLimit . "\n現在のレベル: " . MiningLevelAPI::getInstance()->getLevel($player) . "§r\n\n";
						$this->judgement = false;
					}
					$upgrade = "現在、範囲強化はRank.1[5x5]です\n\n強化効果 : 破壊範囲[5x5]->[7x7]\n\n以下のコストを支払ってMiningToolを強化しますか？\n\n";
					$cost = 'コストは' . self::Rank2_MoneyCost . "円と\nMiningToolsEnchantCostItem " . self::Rank2_ItemCost . "個のアイテム\nをインベントリに保持している必要があります";
					break;
				case 2:
					if (MiningLevelAPI::getInstance()->getLevel($player) < self::Rank3_MiningLevelLimit) {
						$limit = "§cレベルが足りない為強化出来ません。\n要求レベル: " . self::Rank3_MiningLevelLimit . "\n現在のレベル: " . MiningLevelAPI::getInstance()->getLevel($player) . "§r\n\n";
						$this->judgement = false;
					}
					$upgrade = "現在、範囲強化はRank.2[7x7]です\n\n強化効果 : 破壊範囲[7x7]->[9x9]\n\n以下のコストを支払ってMiningToolを強化しますか？\n\n";
					$cost = 'コストは' . self::Rank3_MoneyCost . "円と\nMiningToolsEnchantCostItem " . self::Rank3_ItemCost . "個のアイテム\nをインベントリに保持している必要があります";
					break;
				case 3:
					$upgrade = '最上位ランクの為アップグレードに対応していません';
					break;
				default:
					throw new Error('rank4以上の値が入力されました');
			}
		} elseif ($namedTag->getTag('MiningTools_3') !== null) {
			$this->nbt = ['MiningTools_3' => $namedTag->getInt('MiningTools_3')];
			if (MiningLevelAPI::getInstance()->getLevel($player) < self::Rank1_MiningLevelLimit) {
				$limit = "§cレベルが足りない為強化出来ません。\n要求レベル: " . self::Rank1_MiningLevelLimit . "\n現在のレベル: " . MiningLevelAPI::getInstance()->getLevel($player) . "§r\n\n";
				$this->judgement = false;
			}
			$upgrade = "現在、範囲強化はされていません\n\n強化効果 : 破壊範囲[3x3]->[5x5]\n\n以下のコストを支払ってMiningToolを強化しますか？";
			$cost = 'コストは' . self::Rank1_MoneyCost . "円と\nMiningToolsEnchantCostItem " . self::Rank1_ItemCost . "個のアイテム\nをインベントリに保持している必要があります";
		} else {
			throw new Error('nbtタグが存在しない為不正な挙動として処理しました');
		}
		$this
			->setTitle('Expansion Mining Tools')
			->setText("{$limit}{$upgrade}{$cost}")
			->addElements(new Button('アップデートする'));
	}

	public function handleSubmit(Player $player) : void {
		if (empty($this->nbt)) {
			throw new Error('nbtタグが存在しない為不正な挙動として処理しました');
		}
		if ($this->judgement === false) return;
		if ($player->getInventory()->getItemInHand()->getNamedTag()->getTag('MiningTools_Expansion_Range') !== null) {
			if ($player->getInventory()->getItemInHand()->getNamedTag()->getInt('MiningTools_Expansion_Range') === 3) {
				return;
			}
		}
		SendForm::Send($player, (new RangeBuyForm($player, $this->nbt)));
	}
}
