<?php

namespace lazyperson0710\PlayerSetting\form;

use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\element\Toggle;
use bbo51dog\bboform\form\CustomForm;
use deceitya\miningtools\setting\MiningToolSettings;
use lazyperson0710\PlayerSetting\object\PlayerSettingPool;
use lazyperson0710\PlayerSetting\object\settings\miningTools\AndesiteToStoneSetting;
use lazyperson0710\PlayerSetting\object\settings\miningTools\CobblestoneToStoneSetting;
use lazyperson0710\PlayerSetting\object\settings\miningTools\DioriteToStoneSetting;
use lazyperson0710\PlayerSetting\object\settings\miningTools\GoldIngotSetting;
use lazyperson0710\PlayerSetting\object\settings\miningTools\GraniteToStoneSetting;
use lazyperson0710\PlayerSetting\object\settings\miningTools\GrassToDirtSetting;
use lazyperson0710\PlayerSetting\object\settings\miningTools\IronIngotSetting;
use lazyperson0710\PlayerSetting\object\settings\miningTools\SandToGlassSetting;
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
            if (MiningToolSettings::getInstance()->checkData($player, $value)) {
                $empty = false;
                $this->addElement(
                    match ($value) {
                        '草ブロックを土にする' => $this->grassToDirt = new Toggle("§l> {$value}\n§r機能のon/off", $setting->getSetting(GrassToDirtSetting::getName())?->getValue()),
                        '丸石を石にする' => $this->cobblestoneToStone = new Toggle("§l> {$value}\n§r機能のon/off", $setting->getSetting(CobblestoneToStoneSetting::getName())?->getValue()),
                        '花崗岩を石にする' => $this->graniteToStone = new Toggle("§l> {$value}\n§r機能のon/off", $setting->getSetting(GraniteToStoneSetting::getName())?->getValue()),
                        '閃緑岩を石にする' => $this->dioriteToStone = new Toggle("§l> {$value}\n§r機能のon/off", $setting->getSetting(DioriteToStoneSetting::getName())?->getValue()),
                        '安山岩を石にする' => $this->andesiteToStone = new Toggle("§l> {$value}\n§r機能のon/off", $setting->getSetting(AndesiteToStoneSetting::getName())?->getValue()),
                        '鉄の自動精錬' => $this->sandToGlass = new Toggle("§l> {$value}\n§r機能のon/off", $setting->getSetting(SandToGlassSetting::getName())?->getValue()),
                        '金の自動精錬' => $this->ironIngot = new Toggle("§l> {$value}\n§r機能のon/off", $setting->getSetting(IronIngotSetting::getName())?->getValue()),
                        '砂をガラスにする' => $this->goldIngot = new Toggle("§l> {$value}\n§r機能のon/off", $setting->getSetting(GoldIngotSetting::getName())?->getValue())
                    },
                );
            }
        }
        if ($empty) {
            $this->addElement(new Label("§l> 現在解放されている設定項目が存在しません\n§r/mtから設定を解放してください"));
        }
    }

    public function handleClosed(Player $player): void {
        $player->sendMessage("§bPlayerSettings §7>> §c設定の保存をキャンセルしました");
    }

    public function handleSubmit(Player $player): void {
        $setting = PlayerSettingPool::getInstance()->getSettingNonNull($player);
        if ($setting->getSetting(GrassToDirtSetting::getName())?->getValue() !== $this->grassToDirt->getValue()) {
            $setting->getSetting(GrassToDirtSetting::getName())?->setValue($this->grassToDirt->getValue());
        }
        if ($setting->getSetting(CobblestoneToStoneSetting::getName())?->getValue() !== $this->cobblestoneToStone->getValue()) {
            $setting->getSetting(CobblestoneToStoneSetting::getName())?->setValue($this->cobblestoneToStone->getValue());
        }
        if ($setting->getSetting(GraniteToStoneSetting::getName())?->getValue() !== $this->graniteToStone->getValue()) {
            $setting->getSetting(GraniteToStoneSetting::getName())?->setValue($this->graniteToStone->getValue());
        }
        if ($setting->getSetting(DioriteToStoneSetting::getName())?->getValue() !== $this->dioriteToStone->getValue()) {
            $setting->getSetting(DioriteToStoneSetting::getName())?->setValue($this->dioriteToStone->getValue());
        }
        if ($setting->getSetting(AndesiteToStoneSetting::getName())?->getValue() !== $this->andesiteToStone->getValue()) {
            $setting->getSetting(AndesiteToStoneSetting::getName())?->setValue($this->andesiteToStone->getValue());
        }
        if ($setting->getSetting(SandToGlassSetting::getName())?->getValue() !== $this->sandToGlass->getValue()) {
            $setting->getSetting(SandToGlassSetting::getName())?->setValue($this->sandToGlass->getValue());
        }
        if ($setting->getSetting(IronIngotSetting::getName())?->getValue() !== $this->ironIngot->getValue()) {
            $setting->getSetting(IronIngotSetting::getName())?->setValue($this->ironIngot->getValue());
        }
        if ($setting->getSetting(GoldIngotSetting::getName())?->getValue() !== $this->goldIngot->getValue()) {
            $setting->getSetting(GoldIngotSetting::getName())?->setValue($this->goldIngot->getValue());
        }
        $player->sendMessage("§bPlayerSettings §7>> §a設定を保存しました");
    }

}