<?php

declare(strict_types = 0);

namespace lazyperson0710\ticket\command\form;

use bbo51dog\bboform\element\Input;
use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\form\CustomForm;
use lazyperson0710\ticket\TicketAPI;
use lazyperson710\core\packet\SendMessage\SendMessage;
use onebone\economyapi\EconomyAPI;
use pocketmine\player\Player;
use function is_numeric;

class PurchaseTicketForm extends CustomForm {

    private Label $label;
    private Input $int;

    public function __construct() {
        $this->label = new Label("チケットを購入できます\nチケットは1枚1200円です");
        $this->int = new Input('購入したい枚数を入力してください', 'int');
        $this
            ->setTitle('Ticket')
            ->addElements(
                $this->label,
                $this->int,
            );
    }

    public function handleSubmit(Player $player) : void {
        if ($this->int->getValue() === '') {
            SendMessage::Send($player, '譲渡したいTicketの枚数を入力してください', 'Ticket', false);
            return;
        }
        if (is_numeric($this->int->getValue()) === false) {
            SendMessage::Send($player, '整数のみ入力してください', 'Ticket', false);
            return;
        }
        $cost = $this->int->getValue() * 1200;
        if ($cost > EconomyAPI::getInstance()->myMoney($player)) {
            $cost = $cost - EconomyAPI::getInstance()->myMoney($player);
            SendMessage::Send($player, 'お金が' . $cost . '円足りないため実行出来ませんでした', 'Ticket', false);
            return;
        }
        EconomyAPI::getInstance()->reduceMoney($player, $cost);
        TicketAPI::getInstance()->addTicket($player, $this->int->getValue());
        SendMessage::Send($player, "{$this->int->getValue()}枚のチケットを購入しました！", 'Ticket', true);
    }
}
