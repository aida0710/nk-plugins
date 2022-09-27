<?php

namespace lazyperson0710\LoginBonus\form\convert;

use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\form\CustomForm;
use lazyperson0710\LoginBonus\calculation\CheckInventoryCalculation;
use lazyperson0710\ticket\TicketAPI;
use pocketmine\player\Player;

class TicketConvertConfirmationForm extends CustomForm {

    private int $cost;
    private int $quantity;

    public function __construct(int $cost, int $quantity) {
        $this->cost = $cost;
        $this->quantity = $quantity;
        $this
            ->setTitle("Login Bonus")
            ->addElements(
                new Label("以下の枚数のTicketと交換しますか？"),
                new Label("交換Ticket枚数 : " . $quantity . "枚"),
                new Label("交換コスト : " . $cost . "個"),
            );
    }

    public function handleSubmit(Player $player): void {
        if (CheckInventoryCalculation::check($player, $this->cost)) {
            TicketAPI::getInstance()->addTicket($player, $this->quantity);
            $player->sendMessage("§aログインボーナスを" . $this->cost . "個消費してチケット" . $this->quantity . "枚に交換しました");
        } else {
            $player->sendMessage("コストアイテムの所持数量が必要個数より少ない為処理を中断しました");
        }
    }

}