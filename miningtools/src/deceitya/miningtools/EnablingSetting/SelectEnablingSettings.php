<?php

namespace deceitya\miningtools\EnablingSetting;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\miningtools\element\ConfirmationEnablingSettingButton;
use lazyperson0710\PlayerSetting\object\PlayerSettingPool;
use lazyperson0710\PlayerSetting\object\settings\miningToolsEnablingSetting\EnablingAndesiteToStoneSetting;
use lazyperson0710\PlayerSetting\object\settings\miningToolsEnablingSetting\EnablingCobblestoneToStoneSetting;
use lazyperson0710\PlayerSetting\object\settings\miningToolsEnablingSetting\EnablingDioriteToStoneSetting;
use lazyperson0710\PlayerSetting\object\settings\miningToolsEnablingSetting\EnablingGoldIngotSetting;
use lazyperson0710\PlayerSetting\object\settings\miningToolsEnablingSetting\EnablingGraniteToStoneSetting;
use lazyperson0710\PlayerSetting\object\settings\miningToolsEnablingSetting\EnablingGrassToDirtSetting;
use lazyperson0710\PlayerSetting\object\settings\miningToolsEnablingSetting\EnablingIronIngotSetting;
use lazyperson0710\PlayerSetting\object\settings\miningToolsEnablingSetting\EnablingSandToGlassSetting;
use pocketmine\player\Player;

class SelectEnablingSettings extends SimpleForm {

    public function __construct(Player $player, ?string $exception = null) {
        $settings = [
            EnablingGrassToDirtSetting::getName(),
            EnablingCobblestoneToStoneSetting::getName(),
            EnablingGraniteToStoneSetting::getName(),
            EnablingDioriteToStoneSetting::getName(),
            EnablingAndesiteToStoneSetting::getName(),
            EnablingIronIngotSetting::getName(),
            EnablingGoldIngotSetting::getName(),
            EnablingSandToGlassSetting::getName(),
        ];
        $this
            ->setTitle("Mining Tools")
            ->setText("選択してください\n{$exception}");
        foreach ($settings as $setting) {
            $this->addElement($this->checkEnablingSetting($player, $setting));
        }
    }

    private function checkEnablingSetting(Player $player, string $settingName): ConfirmationEnablingSettingButton {
        if (PlayerSettingPool::getInstance()->getSettingNonNull($player)->getSetting($settingName)->getValue()) {
            $text = "§c既に有効化されています";
            $approval = true;
        } else {
            $text = "§a有効化するには選択して解放してください";
            $approval = false;
        }
        return match ($settingName) {
            "EnablingGrassToDirt" => new ConfirmationEnablingSettingButton("草ブロックを土にする\n{$text}", $settingName, "草ブロックを土にする", $approval),
            "EnablingCobblestoneToStone" => new ConfirmationEnablingSettingButton("丸石を石にする\n{$text}", $settingName, "丸石を石にする", $approval),
            "EnablingGraniteToStone" => new ConfirmationEnablingSettingButton("花崗岩を石にする\n{$text}", $settingName, "花崗岩を石にする", $approval),
            "EnablingDioriteToStone" => new ConfirmationEnablingSettingButton("閃緑岩を石にする\n{$text}", $settingName, "閃緑岩を石にする", $approval),
            "EnablingAndesiteToStone" => new ConfirmationEnablingSettingButton("安山岩を石にする\n{$text}", $settingName, "安山岩を石にする", $approval),
            "EnablingIronIngot" => new ConfirmationEnablingSettingButton("鉄の自動精錬\n{$text}", $settingName, "鉄の自動精錬", $approval),
            "EnablingGoldIngot" => new ConfirmationEnablingSettingButton("金の自動精錬\n{$text}", $settingName, "金の自動精錬", $approval),
            "EnablingSandToGlass" => new ConfirmationEnablingSettingButton("砂をガラスにする\n{$text}", $settingName, "砂をガラスにする", $approval),
        };
    }
}