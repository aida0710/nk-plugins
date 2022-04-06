<?php

namespace lazyperson710\sff\form;

use bbo51dog\bboform\element\Dropdown;
use bbo51dog\bboform\form\CustomForm;
use pocketmine\player\Player;
use pocketmine\Server;

class PlayerInfoForm extends CustomForm {

    private Dropdown $dropdown;

    public function __construct(Player $player) {
        $names = null;
        foreach (Server::getInstance()->getOnlinePlayers() as $player) {
            $name = $player->getName();
            if (Server::getInstance()->isOp($name)) {
                if (!Server::getInstance()->isOp($player->getName())) {
                    continue;
                }
            }
            /*if ($player->getName() === $name) {
                continue;
            }*/
            $names[] .= $name;
        }
        if (is_null($names)) {//こんなことは存在しないけど一応条件分岐だけ(上記のコメントアウトを消したら必要になります)
            $names[] .= "表示可能なプレイヤーが存在しません";
        }
        $this->dropdown = new Dropdown("情報を取得したいプレイヤーを選択してください", $names);
        $this
            ->setTitle("Player Info")
            ->addElement($this->dropdown);
    }

    public function handleSubmit(Player $player): void {
        $playerName = $this->dropdown->getSelectedOption();
        if (!Server::getInstance()->getPlayerByPrefix($playerName)) {
            $player->sendMessage("§bPlayerInfo §7>> §cプレイヤーが存在しない為、正常にformを送信できませんでした");
            return;
        }
        $player->sendForm(new PlayerForm($player, $playerName));
    }
}