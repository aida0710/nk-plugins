<?php

namespace lazyperson0710\ticket\command\form;

use bbo51dog\bboform\element\Dropdown;
use bbo51dog\bboform\element\Input;
use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\form\CustomForm;
use lazyperson0710\ticket\TicketAPI;
use pocketmine\player\Player;
use pocketmine\Server;

class ReplaceTicketForm extends CustomForm {

    private Dropdown $playerList;
    private Label $label;

    public function __construct(Player $player) {
        $names = null;
        foreach (Server::getInstance()->getOnlinePlayers() as $playerName) {
            $name = $player->getName();
            $names[] .= $name;
        }
        if (is_null($names)) {
            $names[] .= "表示可能なプレイヤーが存在しません";
        }
        $this->playerList = new Dropdown("情報を操作したいプレイヤーを選択してください", $names);
        $this->label = new Label("古いTicketを変換してもよろしいでしょうか");
        $this
            ->setTitle("Ticket");
        if(Server::getInstance()->isOp($player->getName())){
            $this->addElement($this->playerList);
        } else {
            $this->addElement($this->label);
        }

    }

    public function handleSubmit(Player $player): void {
        if(Server::getInstance()->isOp($player->getName())){
            $playerName = $this->playerList->getSelectedOption();
            if (!Server::getInstance()->getPlayerByPrefix($playerName)) {
                $player->sendMessage("§bTicket §7>> §cプレイヤーが存在しない為、正常にformを送信できませんでした");
                return;
            }
            $result = TicketAPI::getInstance()->replaceTicket(Server::getInstance()->getPlayerByPrefix($playerName));
            if ($result === false){
                $player->sendMessage("§bTicket §7>> §c交換可能なTicketはありませんでした");
            }
            Server::getInstance()->getPlayerByPrefix($playerName)->sendMessage("§bTicket §7>> §a{$result}個のTicketを交換しました");
            $player->sendMessage("§bTicket §7>> §a{$result}個のTicketを交換しました");
        } else {
            $result = TicketAPI::getInstance()->replaceTicket($player);
            if ($result === false){
                $player->sendMessage("§bTicket §7>> §c交換可能なTicketはありませんでした");
            }
            $player->sendMessage("§bTicket §7>> §a{$result}個のTicketを交換しました");
        }
    }
}