<?php

declare(strict_types = 0);

namespace lazyperson710\edit\form\item;

use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\element\Toggle;
use bbo51dog\bboform\form\CustomForm;
use lazyperson710\core\packet\SendMessage\SendMessage;
use pocketmine\item\Durable;
use pocketmine\player\Player;

class UnbreakableForm extends CustomForm {

    private Toggle $setUnbreakable;

    public function __construct(Player $player) {
        $itemInHand = $player->getInventory()->getItemInHand();
        if (!($itemInHand instanceof Durable)) {
            $this
                ->setTitle('Item Edit')
                ->addElements(
                    new Label('現在所持しているアイテムは道具ではありません'),
                );
            return;
        }
        if ($itemInHand->isUnbreakable()) {
            $isUnbreakable = '現在所持しているアイテムは耐久が無限です';
        } else $isUnbreakable = '現在所持しているアイテムは耐久が有限です';
        $this->setUnbreakable = new Toggle('耐久を有限/無限に変更');
        $this
            ->setTitle('Item Edit')
            ->addElements(
                new Label($isUnbreakable),
                $this->setUnbreakable,
            );
    }

    public function handleSubmit(Player $player) : void {
        $itemInHand = $player->getInventory()->getItemInHand();
        if (!($itemInHand instanceof Durable)) return;
        if ($this->setUnbreakable->getValue() === true) {
            $itemInHand->setUnbreakable(true);
            SendMessage::Send($player, '耐久が無限に変更されました', 'ItemEdit', false);
        } else {
            $itemInHand->setUnbreakable(false);
            SendMessage::Send($player, '耐久が有限に変更されました', 'ItemEdit', false);
        }
        $player->getInventory()->setItemInHand($itemInHand);
    }
}
