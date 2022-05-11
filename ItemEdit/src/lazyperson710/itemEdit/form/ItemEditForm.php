<?php

namespace lazyperson710\itemEdit\form;

use bbo51dog\bboform\element\Input;
use bbo51dog\bboform\element\Label;
use bbo51dog\bboform\form\CustomForm;
use pocketmine\player\Player;

class ItemEditForm extends CustomForm {

    private Input $setName;
    private Input $setLore;
    private Input $setCount;

    public function __construct() {
        $this->setName = new Input(
            "setName",
            "string",
        );
        $this->setLore = new Input(
            "setLore",
            'string(\nで改行できます)',
        );
        $this->setCount = new Input(
            "setCount",
            "int",
        );
        $this
            ->setTitle("Item Edit")
            ->addElements(
                new Label("入力しない場合は変更されません"),
                $this->setName,
                $this->setLore,
                $this->setCount,
            );
    }

    public function handleSubmit(Player $player): void {
        $itemInHand = $player->getInventory()->getItemInHand();
        if (!$this->setName->getValue() == "") {
            $itemInHand->setCustomName($this->setName->getValue());
        }
        if (!$this->setLore->getValue() == "") {
            $setLore = explode('\n', $this->setLore->getValue());
            $itemInHand->setLore($setLore);
        }
        if (!$this->setCount->getValue() == "") {
            if (!preg_match('/^[0-9]+$/', $this->setCount->getValue())) {
                $player->sendMessage("setCount -> 整数のみで入力してください");
            } elseif ($this->setCount->getValue() >= 65) {
                $player->sendMessage("setCount -> 65以上は指定できません");
            } else {
                $itemInHand->setCount($this->setCount->getValue());
                $player->getInventory()->setItemInHand($itemInHand);
            }
        }
        $player->getInventory()->setItemInHand($itemInHand);
    }
}