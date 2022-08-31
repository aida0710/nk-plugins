<?php

namespace deceitya\miningtools\EnablingSetting;

use bbo51dog\bboform\form\SimpleForm;
use lazyperson0710\PlayerSetting\object\PlayerSettingPool;
use lazyperson0710\PlayerSetting\object\settings\miningToolsEnablingSetting\EnablingAndesiteToStoneSetting;
use pocketmine\player\Player;

class SelectEnablingSettings extends SimpleForm {

    public function __construct(Player $player) {
        $this
            ->setTitle("Mining Tools")
            ->setText("");
        $setting = PlayerSettingPool::getInstance()->getSettingNonNull($player);
        if ($setting->getSetting(EnablingAndesiteToStoneSetting::getName())?->getValue() === true) {
            $this->addElement()
        }
    }
}