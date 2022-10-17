<?php

namespace lazyperson710\sff\form;

use bbo51dog\bboform\form\SimpleForm;
use lazyperson710\sff\element\CommandDispatchButton;
use pocketmine\player\Player;

class LockForm extends SimpleForm {

    public function __construct(Player $player) {
        $this
            ->setTitle("Lock System")
            ->setText("使用したい機能を選択してください")
            ->addElements(
                new CommandDispatchButton("チェストをロック", "lockc", null),
                new CommandDispatchButton("額縁をロック", "lockfr", null),
                new CommandDispatchButton("額縁をアンロック", "unlockfr", null),
            );
    }
}