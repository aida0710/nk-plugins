<?php

declare(strict_types = 0);

namespace lazyperson710\sff\form\playerInfo;

use bbo51dog\bboform\element\Dropdown;
use bbo51dog\bboform\form\CustomForm;
use lazyperson710\core\packet\SendForm;
use lazyperson710\core\packet\SendMessage\SendMessage;
use pocketmine\player\Player;
use pocketmine\Server;
use function is_null;

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
            $names[] .= '表示可能なプレイヤーが存在しません';
        }
        $this->dropdown = new Dropdown('情報を取得したいプレイヤーを選択してください', $names);
        $this
            ->setTitle('Player Info')
            ->addElement($this->dropdown);
    }

    public function handleSubmit(Player $player) : void {
        $playerName = $this->dropdown->getSelectedOption();
        if (!Server::getInstance()->getPlayerByPrefix($playerName)) {
            SendMessage::Send($player, 'プレイヤーが存在しない為、正常にformを送信できませんでした', 'PlayerInfo', false);
            return;
        }
        SendForm::Send($player, (new PlayerForm($player, $playerName)));
    }
}
