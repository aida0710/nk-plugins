<?php

namespace lazyperson710\sff\form;

use bbo51dog\bboform\element\Input;
use bbo51dog\bboform\form\CustomForm;
use lazyperson710\core\packet\SoundPacket;
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
            $player->sendMessage("§bCmdStorage §7>> §c実行させたいコマンドを入力してください");
            SoundPacket::Send($player, 'note.bass');
            return;
        }
        if (str_contains($this->input1->getValue(), 'tell')) {
            $player->sendMessage("§bCmdStorage §7>> §ctellコマンドは追加できません");
            SoundPacket::Send($player, 'note.bass');
            return;
        }
        if (str_contains($this->input1->getValue(), 'msg')) {
            $player->sendMessage("§bCmdStorage §7>> §cmsgコマンドは追加できません");
            SoundPacket::Send($player, 'note.bass');
            return;
        }
        if (str_contains($this->input1->getValue(), 'me')) {
            $player->sendMessage("§bCmdStorage §7>> §cmeコマンドは追加できません");
            SoundPacket::Send($player, 'note.bass');
            return;
        }
        //$player->getInventory()->getItemInHand()->setCustomName($this->input1->getValue());
        $item = $player->getInventory()->getItemInHand()->setCustomName($this->input1->getValue());
        $player->getInventory()->setItemInHand($item);
        $player->sendMessage("§bCmdStorage §7>> §a{$this->input1->getValue()}に設定されました");
        SoundPacket::Send($player, 'note.harp');
    }
}