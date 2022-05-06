<?php

namespace lazyperson0710\ticket\command\form;

use bbo51dog\bboform\form\SimpleForm;
use lazyperson0710\ticket\command\form\element\SendFormButton;
use pocketmine\player\Player;

class MainTicketForm extends SimpleForm {

    public function __construct(Player $player) {
        $this
            ->setTitle("Ticket")
            ->addElements(
                new SendFormButton(new CheckTicketForm($player), "Ticket枚数を調べる"),
                new SendFormButton(new SetTicketForm($player), "Ticket枚数を設定する"),
                new SendFormButton(new AddTicketForm($player), "Ticket枚数を増やす"),
                new SendFormButton(new ReduceTicketForm($player), "Ticket枚数を減らす"),
                new SendFormButton(new ReplaceTicketForm($player), "Ticketを変換する"),
                new SendFormButton(new SaveTicketForm($player), "Ticketデータをセーブする"),
            );
    }
}