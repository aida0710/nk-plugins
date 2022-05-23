<?php

namespace lazyperson0710\ticket\command\form;

use bbo51dog\bboform\element\Dropdown;
use bbo51dog\bboform\element\Input;
use bbo51dog\bboform\form\CustomForm;
use lazyperson0710\ticket\TicketAPI;
use pocketmine\player\Player;
use pocketmine\Server;

class ReduceTicketForm extends CustomForm {

    private Dropdown $playerList;
    private Input $int;

    public function __construct(Player $player) {
        $names = null;
        foreach (Server::getInstance()->getOnlinePlayers() as $playerName) {
            $name = $playerName->getName();
            $names[] .= $name;
        }
        if (is_null($names)) {
            $names[] .= "表示可能なプレイヤーが存在しません";
        }
        $this->playerList = new Dropdown("情報を操作したいプレイヤーを選択してください", $names);
        $this->int = new Input("Ticketを減らす数を入力", "int");
        $this
            ->setTitle("Ticket")
            ->addElements(
                $this->playerList,
                $this->int,
            );
    }

    public function handleSubmit(Player $player): void {
        $playerName = $this->playerList->getSelectedOption();
        if (!Server::getInstance()->getPlayerByPrefix($playerName)) {
            $player->sendMessage("§bTicket §7>> §cプレイヤーが存在しない為、正常にformを送信できませんでした");
            return;
        }
        if ($this->int->getValue() === "") {
            $player->sendMessage("§bTicket §7>> §a減算分を入力してください");
            return;
        }
        if (is_numeric($this->int->getValue()) === false) {
            $player->sendMessage("§bTicket §7>> §a整数のみ入力してください");
            return;
        }
        if (TicketAPI::getInstance()->reduceTicket(Server::getInstance()->getPlayerByPrefix($playerName), $this->int->getValue()) === false) {
            $player->sendMessage("§bTicket §7>> §c{$playerName}のTicketを減らせませんでした");
        } else {
            $player->sendMessage("§bTicket §7>> §a{$playerName}のTicketを{$this->int->getValue()}枚減らしました");
        }
    }
}