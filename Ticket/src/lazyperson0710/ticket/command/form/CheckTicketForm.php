<?php

namespace lazyperson0710\ticket\command\form;

use bbo51dog\bboform\element\Dropdown;
use bbo51dog\bboform\element\Input;
use bbo51dog\bboform\form\CustomForm;
use lazyperson0710\ticket\TicketAPI;
use pocketmine\player\Player;
use pocketmine\Server;

class CheckTicketForm extends CustomForm {

    private Dropdown $playerList;

    public function __construct(Player $player) {
        $names = null;
        foreach (Server::getInstance()->getOnlinePlayers() as $player) {
            $name = $player->getName();
            $names[] .= $name;
        }
        if (is_null($names)) {
            $names[] .= "表示可能なプレイヤーが存在しません";
        }
        $this->playerList = new Dropdown("Ticket数を取得したいプレイヤーを選択してください", $names);
        $this
            ->setTitle("Ticket")
            ->addElements(
                $this->playerList,
            );
    }

    public function handleSubmit(Player $player): void {
        $playerName = $this->playerList->getSelectedOption();
        if (!Server::getInstance()->getPlayerByPrefix($playerName)) {
            $player->sendMessage("§bTicket §7>> §cプレイヤーが存在しない為、正常にformを送信できませんでした");
            return;
        }
        $int = TicketAPI::getInstance()->checkData(Server::getInstance()->getPlayerByPrefix($playerName));
        $player->sendMessage("§bTicket §7>> §a{$playerName}のTicketの所持数は{$int}です");
    }
}