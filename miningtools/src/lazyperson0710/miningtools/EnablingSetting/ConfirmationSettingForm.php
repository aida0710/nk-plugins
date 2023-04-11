<?php

declare(strict_types = 0);

namespace lazyperson0710\miningtools\EnablingSetting;

use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\form\CustomForm;
use Error;
use lazyperson0710\miningtools\extensions\CheckPlayerData;
use lazyperson0710\miningtools\extensions\enchant\EnchantFunctionSelectForm;
use lazyperson0710\miningtools\extensions\range\RangeConfirmForm;
use lazyperson0710\PlayerSetting\object\PlayerSettingPool;
use lazyperson710\core\packet\SendForm;
use lazyperson710\core\packet\SoundPacket;
use onebone\economyapi\EconomyAPI;
use pocketmine\block\BlockLegacyIds;
use pocketmine\player\Player;
use function number_format;

class ConfirmationSettingForm extends CustomForm {

	private string $settingName;
	public const CostItemId = BlockLegacyIds::LAPIS_BLOCK;
	public const CostItemNBT = 'EnablingMiningSettingItem';

	private const EnablingGrassToDirt = [
		'money' => 3000000,
		'rangeItem' => 3,
		'enchantItem' => 3,
		'settingItem' => 1,
	];
	private const EnablingCobblestoneToStone = [
		'money' => 15000000,
		'rangeItem' => 5,
		'enchantItem' => 5,
		'settingItem' => 3,
	];
	private const EnablingGraniteToStone = [
		'money' => 3000000,
		'rangeItem' => 3,
		'enchantItem' => 3,
		'settingItem' => 1,
	];
	private const EnablingDioriteToStone = [
		'money' => 3000000,
		'rangeItem' => 3,
		'enchantItem' => 3,
		'settingItem' => 1,
	];
	private const EnablingAndesiteToStone = [
		'money' => 3000000,
		'rangeItem' => 3,
		'enchantItem' => 3,
		'settingItem' => 1,
	];
	private const EnablingIronIngot = [
		'money' => 30000000,
		'rangeItem' => 5,
		'enchantItem' => 5,
		'settingItem' => 5,
	];
	private const EnablingGoldIngot = [
		'money' => 30000000,
		'rangeItem' => 5,
		'enchantItem' => 5,
		'settingItem' => 5,
	];
	private const EnablingSandToGlass = [
		'money' => 8000000,
		'rangeItem' => 3,
		'enchantItem' => 3,
		'settingItem' => 3,
	];

	public function __construct(string $settingName, string $settingJaName) {
		$this->settingName = $settingName;
		$cost = $this->checkCost($settingName);
		$money = number_format($cost['money']);
		$this
			->setTitle('Mining Tools')
			->addElements(
				new Label("本当に解放しますか？\n\n解放する機能 : {$settingJaName}\n\n解放するには以下のコストを消費します"),
				new Label("金額 : {$money}円\nMiningToolsRangeCostItem : {$cost['rangeItem']}個\nMiningToolsEnchantCostItem : {$cost['enchantItem']}個\nMiningSettingItem : {$cost['settingItem']}個"),
				new Label('また、コストアイテムはインベントリに持っておく必要があります'),
			);
	}

	public function checkCost(string $settingName) : array {
		return match ($settingName) {
			'EnablingGrassToDirt' => self::EnablingGrassToDirt,
			'EnablingCobblestoneToStone' => self::EnablingCobblestoneToStone,
			'EnablingGraniteToStone' => self::EnablingGraniteToStone,
			'EnablingDioriteToStone' => self::EnablingDioriteToStone,
			'EnablingAndesiteToStone' => self::EnablingAndesiteToStone,
			'EnablingIronIngot' => self::EnablingIronIngot,
			'EnablingGoldIngot' => self::EnablingGoldIngot,
			'EnablingSandToGlass' => self::EnablingSandToGlass,
		};
	}

	public function handleSubmit(Player $player) : void {
		$settingName = $this->settingName;
		$cost = $this->checkCost($settingName);
		$money = number_format($cost['money']);
		$approval = 0;
		if ((new CheckPlayerData())->CheckReduceMoney($player, $cost['money']) === false) $approval = 1;
		if ((new CheckPlayerData())->CheckCostItem($player, $cost['rangeItem'], RangeConfirmForm::CostItemId, RangeConfirmForm::CostItemNBT) === false) $approval = 2;
		if ((new CheckPlayerData())->CheckCostItem($player, $cost['enchantItem'], EnchantFunctionSelectForm::CostItemId, EnchantFunctionSelectForm::CostItemNBT) === false) $approval = 3;
		if ((new CheckPlayerData())->CheckCostItem($player, $cost['settingItem'], self::CostItemId, self::CostItemNBT) === false) $approval = 4;
		$errorMessage = match ($approval) {
			0 => '正常に処理通過',
			1 => "所持金が足りません。要求金額 : {$money}",
			2 => "MiningToolsRangeCostItemが足りません。要求個数 : {$cost['rangeItem']}",
			3 => "MiningToolsEnchantCostItemが足りません。要求個数 : {$cost['enchantItem']}",
			4 => "EnablingMiningSettingItemが足りません。要求個数 : {$cost['settingItem']}",
		};
		if ($approval !== 0) {
			SendForm::Send($player, new SelectEnablingSettings($player, "要求されたコストを所持していない為設定の有効化が出来ませんでした\n§c{$errorMessage}"));
			SoundPacket::Send($player, 'note.bass');
			return;
		}
		EconomyAPI::getInstance()->reduceMoney($player, $cost['money']);
		if ((new CheckPlayerData())->CheckAndReduceCostItem($player, $cost['rangeItem'], RangeConfirmForm::CostItemId, RangeConfirmForm::CostItemNBT) === false) throw new Error('不明の挙動によりアイテムを取得できませんでしたMiningTools/ConfirmationSettingForm/110');
		if ((new CheckPlayerData())->CheckAndReduceCostItem($player, $cost['enchantItem'], EnchantFunctionSelectForm::CostItemId, EnchantFunctionSelectForm::CostItemNBT) === false) throw new Error('不明の挙動によりアイテムを取得できませんでしたMiningTools/ConfirmationSettingForm/111');
		if ((new CheckPlayerData())->CheckAndReduceCostItem($player, $cost['settingItem'], self::CostItemId, self::CostItemNBT) === false) throw new Error('不明の挙動によりアイテムを取得できませんでしたMiningTools/ConfirmationSettingForm/112');
		PlayerSettingPool::getInstance()->getSettingNonNull($player)->getSetting($settingName)->setValue(true);
		SendForm::Send($player, new SelectEnablingSettings($player, "§a{$settingName}を有効化しました"));
		SoundPacket::Send($player, 'note.harp');
	}

}
