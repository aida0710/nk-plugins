<?php

namespace lazyperson710\itemEdit\form;

use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\element\Toggle;
use bbo51dog\bboform\form\CustomForm;
use pocketmine\item\Durable;
use pocketmine\player\Player;

class UnbreakableForm extends CustomForm {

    private Toggle $setUnbreakable;

    public function __construct(Player $player) {
        $itemInHand = $player->getInventory()->getItemInHand();
        if (!($itemInHand instanceof Durable)) {
            $this
                ->setTitle("Item Edit")
                ->addElements(
                    new Label("現在所持しているアイテムは道具ではありません"),
                );
            return;
        }
        if ($itemInHand->isUnbreakable()) {
            $isUnbreakable = "現在所持しているアイテムは耐久が無限です";
        } else $isUnbreakable = "現在所持しているアイテムは耐久が有限です";
        $this->setUnbreakable = new Toggle("耐久を有限/無限に変更");
        $this
            ->setTitle("Item Edit")
            ->addElements(
                new Label($isUnbreakable),
                $this->setUnbreakable,
            );
    }

    public function handleSubmit(Player $player): void {
        $itemInHand = $player->getInventory()->getItemInHand();
        if (!($itemInHand instanceof Durable)) return;
        if ($this->setUnbreakable->getValue() === true) {
            $itemInHand->setUnbreakable(true);
            $player->sendMessage("耐久が無限に変更されました");
        } else {
            $itemInHand->setUnbreakable(false);
            $player->sendMessage("耐久が有限に変更されました");
        }
        $player->getInventory()->setItemInHand($itemInHand);
    }
}