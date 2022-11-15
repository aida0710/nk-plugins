<?php

namespace lazyperson710\core\packet;

use lazyperson710\core\packet\SendNoSoundMessage\SendNoSoundTip;
use lazyperson710\core\task\IntervalTask;
use pocketmine\form\Form;
use pocketmine\player\Player;

class SendForm {

    public static function Send(Player $player, Form $form): void {
        if (IntervalTask::check($player, 'SendForm')) {
            SendNoSoundTip::Send($player, "0.3秒以内連続でFormを送信することは出来ません", "SendForm", true);
            return;
        } else {
            IntervalTask::onRun($player, 'SendForm', 6);
        }
        $player->sendForm($form);
    }
}