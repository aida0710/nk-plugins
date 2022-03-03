<?php

namespace lazyperson710\sff\form;

use bbo51dog\bboform\element\Input;
use bbo51dog\bboform\form\CustomForm;
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

    public function handleClosed(Player $player): void {
        return;
    }

    public function handleSubmit(Player $player): void {
        if ($this->input1->getValue() === "") {
            $player->sendMessage("§bCmdStorage §7>> §c実行させたいコマンドを入力してください");
            return;
        }
        if (str_contains($this->input1->getValue(), 'tell')) {
            $player->sendMessage("§bCmdStorage §7>> §ctellコマンドは追加できません");
            return;
        }
        if (str_contains($this->input1->getValue(), 'msg')) {
            $player->sendMessage("§bCmdStorage §7>> §cmsgコマンドは追加できません");
            return;
        }
        if (str_contains($this->input1->getValue(), 'me')) {
            $player->sendMessage("§bCmdStorage §7>> §cmeコマンドは追加できません");
            return;
        }
        //$player->getInventory()->getItemInHand()->setCustomName($this->input1->getValue());
        $item = $player->getInventory()->getItemInHand()->setCustomName($this->input1->getValue());
        $player->getInventory()->setItemInHand($item);
        $player->sendMessage("§bCmdStorage §7>> §a{$this->input1->getValue()}に設定されました");
    }
}