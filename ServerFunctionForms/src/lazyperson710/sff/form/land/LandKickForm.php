<?php

namespace lazyperson710\sff\form\land;

use bbo51dog\bboform\element\Input;
use bbo51dog\bboform\form\CustomForm;
use lazyperson710\core\packet\SendForm;
use lazyperson710\sff\form\LandForm;
use pocketmine\player\Player;
use pocketmine\Server;

class LandKickForm extends CustomForm {

    private Input $input1;
    private Input $input2;

    public function __construct() {
        $this->input1 = new Input(
            "他プレイヤーとの共有を解除したい土地番号を入力してください\n自分の持っている土地は/land whoseか/land hereから調べることが可能です",
            "例: 1",
        );
        $this->input2 = new Input(
            "土地の共有を解除したいプレイヤーを入力\n必ずプレイヤーidは省略しないで記述してください\nまた、オンラインプレイヤーであることを確認してください",
            "例: lazyperson710"
        );
        $this
            ->setTitle("Land Command")
            ->addElements(
                $this->input1,
                $this->input2,
            );
    }

    public function handleClosed(Player $player): void {
        SendForm::Send($player, (new LandForm($player)));
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
        if ($this->input2->getValue() === "") {
            $player->sendMessage("§bLand §7>> §cプレイヤーidを入力してください");
            return;
        }
        if (!Server::getInstance()->getPlayerExact($this->input2->getValue())) {
            $player->sendMessage("§bLand §7>> §cオンラインのプレイヤーを入力してください");
            return;
        }
        Server::getInstance()->dispatchCommand($player, "land kick {$this->input1->getValue()} {$this->input2->getValue()}");
    }

}