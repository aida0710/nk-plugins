<?php

namespace lazyperson0710\ticket\command\form;

use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\form\CustomForm;
use lazyperson0710\ticket\TicketAPI;
use pocketmine\player\Player;

class SaveTicketForm extends CustomForm {

    public function __construct(Player $player) {
        $this
            ->setTitle("Ticket")
            ->addElements(
                new Label("データを保存します"),
            );
    }

    public function handleSubmit(Player $player): void {
        if (TicketAPI::getInstance()->save() === true) {
            $player->sendMessage("Ticketデータをセーブしました");
        } else {
            $player->sendMessage("Ticketデータのセーブに失敗しました");
        }
    }
}