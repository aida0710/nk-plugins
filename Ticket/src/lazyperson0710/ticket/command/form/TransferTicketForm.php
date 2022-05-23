<?php

namespace lazyperson0710\ticket\command\form;

use bbo51dog\bboform\element\Dropdown;
use bbo51dog\bboform\element\Input;
use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\form\CustomForm;
use lazyperson0710\ticket\TicketAPI;
use pocketmine\player\Player;
use pocketmine\Server;

class TransferTicketForm extends CustomForm {

    private Dropdown $playerList;
    private Label $label;
    private Input $int;

    public function __construct(Player $player) {
        $names = null;
        foreach (Server::getInstance()->getOnlinePlayers() as $playerName) {
            $name = $playerName->getName();
            if ($player->getName() === $playerName->getName()) continue;
            $names[] .= $name;
        }
        if (is_null($names)) {
            $names[] .= "表示可能なプレイヤーが存在しません";
        }
        $this->playerList = new Dropdown("プレイヤー", $names);
        $this->label = new Label("チケットの枚数と譲渡したいプレイヤーを選択してください");
        $this->int = new Input("譲渡したい枚数を入力してください", "int");
        $this
            ->setTitle("Ticket")
            ->addElements(
                $this->label,
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
        $playerInstance = Server::getInstance()->getPlayerByPrefix($playerName);
        if ($this->int->getValue() === "") {
            $player->sendMessage("§bTicket §7>> §a譲渡したいTicketの枚数を入力してください");
            return;
        }
        if (is_numeric($this->int->getValue()) === false) {
            $player->sendMessage("§bTicket §7>> §a整数のみ入力してください");
            return;
        }
        if (TicketAPI::getInstance()->reduceTicket(Server::getInstance()->getPlayerByPrefix($playerName), $this->int->getValue()) === false) {
            $player->sendMessage("§bTicket §7>> §c{$playerName}のTicketの枚数が足らないかエラーが発生しました");
            return;
        }
        TicketAPI::getInstance()->addTicket($playerInstance, $this->int->getValue());
        $player->sendMessage("§bTicket §7>> §a{$playerName}さんにTicketを{$this->int->getValue()}枚譲渡しました");
        $playerInstance->sendMessage("§bTicket §7>> §a{$player->getName()}さんからTicketを{$this->int->getValue()}枚プレゼントされました");
    }
}
