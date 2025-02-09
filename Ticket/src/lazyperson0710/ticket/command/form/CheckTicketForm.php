<?php

declare(strict_types = 0);

namespace lazyperson0710\ticket\command\form;

use bbo51dog\bboform\element\Dropdown;
use bbo51dog\bboform\form\CustomForm;
use lazyperson0710\ticket\TicketAPI;
use lazyperson710\core\packet\SendMessage\SendMessage;
use pocketmine\player\Player;
use pocketmine\Server;
use function is_null;

class CheckTicketForm extends CustomForm {

    private Dropdown $playerList;

    public function __construct(Player $player) {
        $names = null;
        foreach (Server::getInstance()->getOnlinePlayers() as $playerName) {
            $name = $playerName->getName();
            $names[] .= $name;
        }
        if (is_null($names)) {
            $names[] .= '表示可能なプレイヤーが存在しません';
        }
        $this->playerList = new Dropdown('Ticket数を取得したいプレイヤーを選択してください', $names);
        $this
            ->setTitle('Ticket')
            ->addElements(
                $this->playerList,
            );
    }

    public function handleSubmit(Player $player) : void {
        $playerName = $this->playerList->getSelectedOption();
        if (!Server::getInstance()->getPlayerByPrefix($playerName)) {
            SendMessage::Send($player, 'プレイヤーが存在しない為、正常にformを送信できませんでした', 'Ticket', false);
            return;
        }
        if (TicketAPI::getInstance()->checkData(Server::getInstance()->getPlayerByPrefix($playerName)) !== false) {
            $int = TicketAPI::getInstance()->checkData(Server::getInstance()->getPlayerByPrefix($playerName));
            SendMessage::Send($player, "{$playerName}のTicketの所持数は{$int}です", 'Ticket', true);
        } else {
            SendMessage::Send($player, "{$playerName}のTicketデータは存在しません", 'Ticket', false);
        }
    }
}
