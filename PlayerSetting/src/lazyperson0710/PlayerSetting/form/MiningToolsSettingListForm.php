<?php

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

    public function __construct(Player $player) {
        $setting = PlayerSettingPool::getInstance()->getSettingNonNull($player);
        $this->setTitle("PlayerSettings");
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

    public function handleClosed(Player $player): void {
        SendForm::Send($player, new SelectSettingForm($player, "\n§cFormを閉じたため、設定は保存されませんでした"));
    }

    public function handleSubmit(Player $player): void {
        $setting = PlayerSettingPool::getInstance()->getSettingNonNull($player);
        $settingNames = [
            GrassToDirtSetting::getName() => $this->grassToDirt,
            CobblestoneToStoneSetting::getName() => $this->cobblestoneToStone,
            GraniteToStoneSetting::getName() => $this->graniteToStone,
            DioriteToStoneSetting::getName() => $this->dioriteToStone,
            AndesiteToStoneSetting::getName() => $this->andesiteToStone,
            SandToGlassSetting::getName() => $this->sandToGlass,
            IronIngotSetting::getName() => $this->ironIngot,
            GoldIngotSetting::getName() => $this->goldIngot,
        ];
        foreach ($settingNames as $name => $toggle) {
            if ($setting->getSetting($name)?->getValue() === true) {
                if ($setting->getSetting($name)?->getValue() !== $toggle->getValue()) {
                    $setting->getSetting($name)?->setValue($toggle->getValue());
                }
            }
        }
        SendForm::Send($player, new SelectSettingForm($player, "\n§a設定を保存しました"));
    }

}