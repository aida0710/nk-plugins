<?php

namespace lazyperson710\sff\form;

use bbo51dog\bboform\element\Input;
use bbo51dog\bboform\form\CustomForm;
use lazyperson710\core\packet\SendMessage\SendMessage;
use pocketmine\player\Player;

class CommandStorageForm extends CustomForm {

    private Input $input1;

    public function __construct() {
        $this->input1 = new Input(
            "設定したいコマンドを記入してください\n\n\n/は不要です\ntell,msg,meコマンドは設定できません",
            "例: stall",
        );
        $this
            ->setTitle("Command Storage")
            ->addElement($this->input1);
    }

    public function handleSubmit(Player $player): void {
        if ($this->input1->getValue() === "") {
            SendMessage::Send($player, "実行させたいコマンドを入力してください", "CmdStorage", false);
            return;
        }
        if (str_contains($this->input1->getValue(), 'tell')) {
            SendMessage::Send($player, "tellコマンドは追加できません", "CmdStorage", false);
            return;
        }
        if (str_contains($this->input1->getValue(), 'msg')) {
            SendMessage::Send($player, "msgコマンドは追加できません", "CmdStorage", false);
            return;
        }
        if (str_contains($this->input1->getValue(), 'me')) {
            SendMessage::Send($player, "meコマンドは追加できません", "CmdStorage", false);
            return;
        }
        //$player->getInventory()->getItemInHand()->setCustomName($this->input1->getValue());
        $item = $player->getInventory()->getItemInHand()->setCustomName($this->input1->getValue());
        $player->getInventory()->setItemInHand($item);
        SendMessage::Send($player, "{$this->input1->getValue()}に設定されました", "CmdStorage", true);
    }
}