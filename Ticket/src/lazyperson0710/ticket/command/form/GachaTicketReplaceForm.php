<?php

namespace lazyperson0710\ticket\command\form;

use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\form\CustomForm;
use lazyperson0710\ticket\TicketAPI;
use lazyperson710\core\packet\SoundPacket;
use pocketmine\player\Player;

class GachaTicketReplaceForm extends CustomForm {

    private Label $label;

    public function __construct(Player $player) {
        $this->label = new Label("はずれ石をTicketに変換してもよろしいでしょうか");
        $this
            ->setTitle("Ticket");
        $this->addElement($this->label);
    }

    public function handleSubmit(Player $player): void {
        $count = TicketAPI::getInstance()->InventoryConfirmationTicket($player);
        if ($count >= 1) {
            TicketAPI::getInstance()->addTicket($player, $count);
            $player->sendMessage("§bTicket §7>> §aチケットの変換処理を実行し、{$count}枚のチケットを取得しました");
            SoundPacket::Send($player, 'note.harp');
        } else {
            $player->sendMessage("§bTicket §7>> §c変換するアイテムが存在しませんでした");
            SoundPacket::Send($player, 'note.bass');
        }
    }
}
