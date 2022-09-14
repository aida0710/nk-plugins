<?php

namespace lazyperson710\sff\form\element;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\form\SimpleForm;
use lazyperson710\core\packet\SendForm;
use pocketmine\item\Item;
use pocketmine\player\Player;

class SelectLoginBonusItemButton extends Button {

    private string $title;
    private string $description;
    //item＠$item,
    //quantity＠$quantity,
    //cost＠$cost,
    //customName＠$customName,
    //lore＠$lore,
    //formExplanation＠$formExplanation,
    //todo 作業してない
    public function __construct(Item $item, $quantity, $cost, $customName, $lore, $formExplanation) {
        parent::__construct($text);
        $this->title = $title;
        $this->description = $description;
    }

    public function handleSubmit(Player $player): void {
        $form = (new SimpleForm())
            ->setTitle($this->title)
            ->setText($this->description)
            ->addElement(new Button("閉じる"));
        SendForm::Send($player, ($form));
    }
}