<?php

namespace Deceitya\BonusConvert\Form;

use Deceitya\BonusConvert\BonusConvertPlugin;
use lazyperson710\core\packet\SendForm;
use pocketmine\form\Form;
use pocketmine\player\Player;

class MainForm implements Form {

    public function handleResponse(Player $player, $data): void {
        if ($data === null) {
            return;
        }
        $convert = BonusConvertPlugin::getConverts()[$data];
        SendForm::Send($player, (new SubForm($convert)));
    }

    public function jsonSerialize() {
        $form = [
            'type' => 'form',
            'title' => 'LoginBonus',
            'content' => '選択して下さい',
            'buttons' => [],
        ];
        foreach (BonusConvertPlugin::getConverts() as $convert) {
            $form['buttons'][] = ['text' => "必要数 : {$convert->getNeedCount()}"];
        }
        return $form;
    }
}
