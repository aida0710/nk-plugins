<?php

namespace lazyperson710\edit\form\player;

use bbo51dog\bboform\element\Dropdown;
use bbo51dog\bboform\element\Input;
use bbo51dog\bboform\form\CustomForm;
use pocketmine\player\Player;
use pocketmine\Server;

class InventoryEditPlayer extends CustomForm {

    private Dropdown $players;
    private Input $input;

    public function __construct() {
        $names = null;
        foreach (Server::getInstance()->getOnlinePlayers() as $onlinePlayer) {
            $name = $onlinePlayer->getName();
            $names[] .= $name;
        }
        $this->players = new Dropdown("設定を変更したいプレイヤーを選択してください", $names);
        $this->input = new Input("オフラインのプレイヤーを指定したい場合はこちらを入力してください", "lazyperson710");
        $this
            ->setTitle("Player Edit")
            ->addElements(
                $this->players,
                $this->input,
            );
    }

    public function handleSubmit(Player $player): void {
        $targetName = $this->players->getSelectedOption();
        if (empty($this->input->getValue())) {
            Server::getInstance()->dispatchCommand($player, "invsee " . $targetName);
        } else {
            Server::getInstance()->dispatchCommand($player, "invsee " . $this->input->getValue());
        }
    }

}