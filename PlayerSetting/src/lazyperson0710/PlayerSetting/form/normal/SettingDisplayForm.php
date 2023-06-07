<?php

declare(strict_types = 1);

namespace lazyperson0710\PlayerSetting\form\normal;

use bbo51dog\bboform\form\CustomForm;
use lazyperson0710\PlayerSetting\object\Setting;
use pocketmine\player\Player;

class SettingDisplayForm extends CustomForm {

    public function __construct(
        private Player $player,
        private Setting $setting,
    ) {
    }

}