<?php

declare(strict_types = 0);

namespace lazyperson0710\miningtools\element;

use bbo51dog\bboform\element\Button;
use lazyperson0710\miningtools\EnablingSetting\ConfirmationSettingForm;
use lazyperson0710\miningtools\EnablingSetting\SelectEnablingSettings;
use lazyperson710\core\packet\SendForm;
use pocketmine\player\Player;

class ConfirmationEnablingSettingButton extends Button {

    private string $settingName;
    private string $settingJaName;
    private bool $approval;

    public function __construct(string $text, string $settingName, string $settingJaName, bool $approval) {
        parent::__construct($text);
        $this->approval = $approval;
        $this->settingName = $settingName;
        $this->settingJaName = $settingJaName;
    }

    public function handleSubmit(Player $player) : void {
        if ($this->approval) {
            SendForm::Send($player, new SelectEnablingSettings($player, '既に有効化されている為/settingから設定出来ます'));
        } else {
            SendForm::Send($player, new ConfirmationSettingForm($this->settingName, $this->settingJaName));
        }
    }
}
