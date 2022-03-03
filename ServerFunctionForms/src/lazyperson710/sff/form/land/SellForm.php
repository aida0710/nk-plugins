<?php

namespace lazyperson710\sff\form\land;

use bbo51dog\bboform\element\Input;
use bbo51dog\bboform\form\CustomForm;
use lazyperson710\sff\form\LandForm;
use pocketmine\player\Player;
use pocketmine\Server;

class SellForm extends CustomForm {

    private Input $input1;

    public function __construct() {
        $this->input1 = new Input(
            "売却したい土地の名前を入力してください\n自分の持っている土地は/land whoseか/land hereから調べることが可能です",
            "例: 1",
        );
        $this
            ->setTitle("Land Command")
            ->addElement($this->input1);
    }

    public function handleClosed(Player $player): void {
        $player->sendForm(new LandForm($player));
        return;
    }

    public function handleSubmit(Player $player): void {
        if ($this->input1->getValue() === "") {
            $player->sendMessage("§bLand §7>> §c土地番号を入力してください");
            return;
        } elseif (!preg_match('/^[0-9]+$/', $this->input1->getValue())) {
            $player->sendMessage("§bLand §7>> §c土地番号は数字で入力してください");
            return;
        }
        Server::getInstance()->dispatchCommand($player, "landsell {$this->input1->getValue()}");
    }
}