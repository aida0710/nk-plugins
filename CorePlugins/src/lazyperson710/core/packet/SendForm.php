<?php

namespace lazyperson710\core\packet;

use lazyperson710\core\Main;
use pocketmine\form\Form;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;

class SendForm {

    private static array $formInterval = [];

    public static function Send(Player $player, Form $form): void {
        if (isset(self::$formInterval[$player->getName()])) {
            SendNoSoundTip::Send($player, "0.3秒以内連続でFormを送信することは出来ません", "SendForm", true);
            return;
        } else {
            self::$formInterval[$player->getName()] = true;
            Main::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(
                function () use ($player): void {
                    (new SendForm())->unset($player);
                }
            ), 6);
        }
        $player->sendForm($form);
    }

    public function unset(Player $player): void {
        unset(self::$formInterval[$player->getName()]);
    }
}