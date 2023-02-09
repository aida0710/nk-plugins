<?php

declare(strict_types = 1);
namespace lazyperson0710\PlayerSetting\form;

use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\element\Toggle;
use bbo51dog\bboform\form\CustomForm;
use lazyperson0710\PlayerSetting\object\PlayerSettingPool;
use lazyperson0710\PlayerSetting\object\settings\miningTools\AndesiteToStoneSetting;
use lazyperson0710\PlayerSetting\object\settings\miningTools\CobblestoneToStoneSetting;
use lazyperson0710\PlayerSetting\object\settings\miningTools\DioriteToStoneSetting;
use lazyperson0710\PlayerSetting\object\settings\miningTools\GoldIngotSetting;
use lazyperson0710\PlayerSetting\object\settings\miningTools\GraniteToStoneSetting;
use lazyperson0710\PlayerSetting\object\settings\miningTools\GrassToDirtSetting;
use lazyperson0710\PlayerSetting\object\settings\miningTools\IronIngotSetting;
use lazyperson0710\PlayerSetting\object\settings\miningTools\SandToGlassSetting;
use lazyperson0710\PlayerSetting\object\settings\miningToolsEnablingSetting\EnablingAndesiteToStoneSetting;
use lazyperson0710\PlayerSetting\object\settings\miningToolsEnablingSetting\EnablingCobblestoneToStoneSetting;
use lazyperson0710\PlayerSetting\object\settings\miningToolsEnablingSetting\EnablingDioriteToStoneSetting;
use lazyperson0710\PlayerSetting\object\settings\miningToolsEnablingSetting\EnablingGoldIngotSetting;
use lazyperson0710\PlayerSetting\object\settings\miningToolsEnablingSetting\EnablingGraniteToStoneSetting;
use lazyperson0710\PlayerSetting\object\settings\miningToolsEnablingSetting\EnablingGrassToDirtSetting;
use lazyperson0710\PlayerSetting\object\settings\miningToolsEnablingSetting\EnablingIronIngotSetting;
use lazyperson0710\PlayerSetting\object\settings\miningToolsEnablingSetting\EnablingSandToGlassSetting;
use lazyperson710\core\packet\SendForm;
use lazyperson710\core\packet\SoundPacket;
use pocketmine\player\Player;

class MiningToolsSettingListForm extends CustomForm {

	private Toggle $grassToDirt;
	private Toggle $cobblestoneToStone;
	private Toggle $graniteToStone;
	private Toggle $dioriteToStone;
	private Toggle $andesiteToStone;
	private Toggle $sandToGlass;
	private Toggle $ironIngot;
	private Toggle $goldIngot;
	private array $settings = [
		'草ブロックを土にする',//GrassToDirt
		'丸石を石にする',  //CobblerStoneToStone
		'花崗岩を石にする',//GrassToStone
		'閃緑岩を石にする',//DioriteToStone
		'安山岩を石にする',//AndesiteToStone
		'鉄の自動精錬', //SandToGlass
		'金の自動精錬', //IronIngot
		'砂をガラスにする',   //GoldIngot
	];
	private Player $player;

	public function __construct(Player $player) {
		$this->player = $player;
		$setting = PlayerSettingPool::getInstance()->getSettingNonNull($player);
		$this->setTitle('PlayerSettings');
		$empty = true;
		foreach ($this->settings as $value) {
			$settingName = match ($value) {
				'草ブロックを土にする' => EnablingGrassToDirtSetting::getName(),
				'丸石を石にする' => EnablingCobblestoneToStoneSetting::getName(),
				'花崗岩を石にする' => EnablingGraniteToStoneSetting::getName(),
				'閃緑岩を石にする' => EnablingDioriteToStoneSetting::getName(),
				'安山岩を石にする' => EnablingAndesiteToStoneSetting::getName(),
				'鉄の自動精錬' => EnablingIronIngotSetting::getName(),
				'金の自動精錬' => EnablingGoldIngotSetting::getName(),
				'砂をガラスにする' => EnablingSandToGlassSetting::getName(),
			};
			if (PlayerSettingPool::getInstance()->getSettingNonNull($player)->getSetting($settingName)->getValue()) {
				$empty = false;
				match ($value) {
					'草ブロックを土にする' => $this->grassToDirt = new Toggle("§l> {$value}\n§r§a機能は[オフ | オン]で設定出来ます", $setting->getSetting(GrassToDirtSetting::getName())?->getValue()),
					'丸石を石にする' => $this->cobblestoneToStone = new Toggle("§l> {$value}\n§r§a機能は[オフ | オン]で設定出来ます", $setting->getSetting(CobblestoneToStoneSetting::getName())?->getValue()),
					'花崗岩を石にする' => $this->graniteToStone = new Toggle("§l> {$value}\n§r§a機能は[オフ | オン]で設定出来ます", $setting->getSetting(GraniteToStoneSetting::getName())?->getValue()),
					'閃緑岩を石にする' => $this->dioriteToStone = new Toggle("§l> {$value}\n§r§a機能は[オフ | オン]で設定出来ます", $setting->getSetting(DioriteToStoneSetting::getName())?->getValue()),
					'安山岩を石にする' => $this->andesiteToStone = new Toggle("§l> {$value}\n§r§a機能は[オフ | オン]で設定出来ます", $setting->getSetting(AndesiteToStoneSetting::getName())?->getValue()),
					'砂をガラスにする' => $this->sandToGlass = new Toggle("§l> {$value}\n§r§a機能は[オフ | オン]で設定出来ます", $setting->getSetting(SandToGlassSetting::getName())?->getValue()),
					'鉄の自動精錬' => $this->ironIngot = new Toggle("§l> {$value}\n§r§a機能は[オフ | オン]で設定出来ます", $setting->getSetting(IronIngotSetting::getName())?->getValue()),
					'金の自動精錬' => $this->goldIngot = new Toggle("§l> {$value}\n§r§a機能は[オフ | オン]で設定出来ます", $setting->getSetting(GoldIngotSetting::getName())?->getValue()),
				};
			} else {
				match ($value) {
					'草ブロックを土にする' => $this->grassToDirt = new Toggle("§l> {$value}\n§r§cこの機能は有効化されていません", false),
					'丸石を石にする' => $this->cobblestoneToStone = new Toggle("§l> {$value}\n§r§cこの機能は有効化されていません", false),
					'花崗岩を石にする' => $this->graniteToStone = new Toggle("§l> {$value}\n§r§cこの機能は有効化されていません", false),
					'閃緑岩を石にする' => $this->dioriteToStone = new Toggle("§l> {$value}\n§r§cこの機能は有効化されていません", false),
					'安山岩を石にする' => $this->andesiteToStone = new Toggle("§l> {$value}\n§r§cこの機能は有効化されていません", false),
					'砂をガラスにする' => $this->sandToGlass = new Toggle("§l> {$value}\n§r§cこの機能は有効化されていません", false),
					'鉄の自動精錬' => $this->ironIngot = new Toggle("§l> {$value}\n§r§cこの機能は有効化されていません", false),
					'金の自動精錬' => $this->goldIngot = new Toggle("§l> {$value}\n§r§cこの機能は有効化されていません", false),
				};
			}
		}
		if ($empty) {
			$this->addElement(new Label("§l> 現在解放されている設定項目が存在しません\n§r/mtから設定を解放してください"));
		}
		$this->addElements(
			$this->grassToDirt,
			$this->cobblestoneToStone,
			$this->graniteToStone,
			$this->dioriteToStone,
			$this->andesiteToStone,
			$this->sandToGlass,
			$this->ironIngot,
			$this->goldIngot,
		);
	}

	public function handleClosed(Player $player) : void {
		SendForm::Send($this->player, new SelectSettingForm($player, "\n§cFormを閉じたため、設定は保存されませんでした"));
		SoundPacket::Send($player, 'dig.chain');
	}

	public function handleSubmit(Player $player) : void {
		$setting = PlayerSettingPool::getInstance()->getSettingNonNull($this->player);
		if ($setting->getSetting(EnablingGrassToDirtSetting::getName())?->getValue() === true) {
			if ($setting->getSetting(GrassToDirtSetting::getName())?->getValue() !== $this->grassToDirt->getValue()) {
				$setting->getSetting(GrassToDirtSetting::getName())?->setValue($this->grassToDirt->getValue());
			}
		}
		if ($setting->getSetting(EnablingCobblestoneToStoneSetting::getName())?->getValue() === true) {
			if ($setting->getSetting(CobblestoneToStoneSetting::getName())?->getValue() !== $this->cobblestoneToStone->getValue()) {
				$setting->getSetting(CobblestoneToStoneSetting::getName())?->setValue($this->cobblestoneToStone->getValue());
			}
		}
		if ($setting->getSetting(EnablingGraniteToStoneSetting::getName())?->getValue() === true) {
			if ($setting->getSetting(GraniteToStoneSetting::getName())?->getValue() !== $this->graniteToStone->getValue()) {
				$setting->getSetting(GraniteToStoneSetting::getName())?->setValue($this->graniteToStone->getValue());
			}
		}
		if ($setting->getSetting(EnablingDioriteToStoneSetting::getName())?->getValue() === true) {
			if ($setting->getSetting(DioriteToStoneSetting::getName())?->getValue() !== $this->dioriteToStone->getValue()) {
				$setting->getSetting(DioriteToStoneSetting::getName())?->setValue($this->dioriteToStone->getValue());
			}
		}
		if ($setting->getSetting(EnablingAndesiteToStoneSetting::getName())?->getValue() === true) {
			if ($setting->getSetting(AndesiteToStoneSetting::getName())?->getValue() !== $this->andesiteToStone->getValue()) {
				$setting->getSetting(AndesiteToStoneSetting::getName())?->setValue($this->andesiteToStone->getValue());
			}
		}
		if ($setting->getSetting(EnablingSandToGlassSetting::getName())?->getValue() === true) {
			if ($setting->getSetting(SandToGlassSetting::getName())?->getValue() !== $this->sandToGlass->getValue()) {
				$setting->getSetting(SandToGlassSetting::getName())?->setValue($this->sandToGlass->getValue());
			}
		}
		if ($setting->getSetting(EnablingIronIngotSetting::getName())?->getValue() === true) {
			if ($setting->getSetting(IronIngotSetting::getName())?->getValue() !== $this->ironIngot->getValue()) {
				$setting->getSetting(IronIngotSetting::getName())?->setValue($this->ironIngot->getValue());
			}
		}
		if ($setting->getSetting(EnablingGoldIngotSetting::getName())?->getValue() === true) {
			if ($setting->getSetting(GoldIngotSetting::getName())?->getValue() !== $this->goldIngot->getValue()) {
				$setting->getSetting(GoldIngotSetting::getName())?->setValue($this->goldIngot->getValue());
			}
		}
		SendForm::Send($player, new SelectSettingForm($this->player, "\n§a設定を保存しました"));
		SoundPacket::Send($player, 'item.spyglass.use');
	}

}
