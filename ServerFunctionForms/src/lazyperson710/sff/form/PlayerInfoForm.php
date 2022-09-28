<?php

namespace lazyperson710\sff\form;

use bbo51dog\bboform\element\Dropdown;
use bbo51dog\bboform\form\CustomForm;
use lazyperson710\core\packet\SendForm;
use pocketmine\player\Player;
use pocketmine\Server;

class PlayerInfoForm extends CustomForm {

    private Dropdown $dropdown;

    public function __construct(Player $player) {
        $names = null;
        foreach (Server::getInstance()->getOnlinePlayers() as $onlinePlayer) {
            $name = $onlinePlayer->getName();
            if (Server::getInstance()->isOp($name)) {
                continue;
            }
            if ($player->getName() === $name) {
                continue;
            }
            $names[] .= $name;
        }
        if (is_null($names)) {
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
        SendForm::Send($player, (new PlayerForm($player, $playerName)));
    }
}