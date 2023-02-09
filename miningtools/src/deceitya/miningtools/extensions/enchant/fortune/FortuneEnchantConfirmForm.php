<?php

declare(strict_types = 1);
namespace deceitya\miningtools\extensions\enchant\fortune;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\form\SimpleForm;
use Error;
use lazyperson710\core\packet\SendForm;
use pocketmine\player\Player;

class FortuneEnchantConfirmForm extends SimpleForm {

	private array $nbt = [];
	public const Rank1_MoneyCost = 2500000;
	public const Rank2_MoneyCost = 4500000;
	public const Rank3_MoneyCost = 12000000;

	public const Rank1_ItemCost = 1;
	public const Rank2_ItemCost = 3;
	public const Rank3_ItemCost = 8;

	public function __construct(Player $player) {
		$namedTag = $player->getInventory()->getItemInHand()->getNamedTag();
		if ($namedTag->getTag('MiningTools_Expansion_FortuneEnchant') !== null) {
			$this->nbt = ['MiningTools_Expansion_FortuneEnchant' => $namedTag->getInt('MiningTools_Expansion_FortuneEnchant')];
			$cost = '';
			switch ($namedTag->getInt('MiningTools_Expansion_FortuneEnchant')) {
				case 1:
					$upgrade = "現在幸運エンチャントはRank.1です\n\n強化効果 : 幸運1から幸運2に強化\n\n以下のコストを支払ってMiningToolを強化しますか？";
					$cost = 'コストは' . self::Rank2_MoneyCost . "円と\nMiningToolsEnchantCostItem " . self::Rank2_ItemCost . "個のアイテム\nをインベントリに保持している必要があります";
					break;
				case 2:
					$upgrade = "現在幸運エンチャントはRank.2です\n\n強化効果 : 幸運2から幸運3に強化\n\n以下のコストを支払ってMiningToolを強化しますか？";
					$cost = 'コストは' . self::Rank3_MoneyCost . "円と\nMiningToolsEnchantCostItem " . self::Rank3_ItemCost . "個のアイテム\nをインベントリに保持している必要があります";
					break;
				case 3:
					$upgrade = '最上位ランクの為アップグレードに対応していません';
					break;
				default:
					throw new Error('rank4以上の値が入力されました');
			}
		} else {
			$upgrade = "現在、幸運強化はされていません\n\n強化効果 : シルクタッチを削除し幸運1を付与\n\n以下のコストを支払ってMiningToolを強化しますか？";
			$cost = 'コストは' . self::Rank1_MoneyCost . "円と\nMiningToolsEnchantCostItem " . self::Rank1_ItemCost . "個のアイテム\nをインベントリに保持している必要があります";
		}
		$this
			->setTitle('Expansion Mining Tools')
			->setText("{$upgrade}\n\n{$cost}")
			->addElements(new Button('アップデートする'));
	}

	public function handleSubmit(Player $player) : void {
		if ($player->getInventory()->getItemInHand()->getNamedTag()->getTag('MiningTools_Expansion_FortuneEnchant') !== null) {
			if ($player->getInventory()->getItemInHand()->getNamedTag()->getInt('MiningTools_Expansion_FortuneEnchant') === 3) {
				return;
			}
		}
		SendForm::Send($player, (new FortuneEnchantBuyForm($player, $this->nbt)));
	}
}
