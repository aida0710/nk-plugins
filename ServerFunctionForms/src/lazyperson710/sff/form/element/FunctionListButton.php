<?php

namespace lazyperson710\sff\form\element;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\form\SimpleForm;
use pocketmine\player\Player;

class FunctionListButton extends Button {

    private string $title;
    private string $description;

    public function __construct(string $title, string $text, string $description) {
        parent::__construct($text);
        $this->title = $title;
        $this->description = $description;
    }

    public function handleSubmit(Player $player): void {
        $form = (new SimpleForm())
            ->setTitle($this->title)
            ->setText($this->description)
            ->addElement(new Button("閉じる"));
        $player->sendForm($form);
    }
}