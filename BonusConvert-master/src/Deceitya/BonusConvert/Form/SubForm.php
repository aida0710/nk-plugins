<?php

namespace Deceitya\BonusConvert\Form;

use Deceitya\BonusConvert\Convert;
use pocketmine\form\Form;
use pocketmine\player\Player;

class SubForm implements Form {

    /** @var Convert */
    private $convert;

    public function __construct(Convert $convert) {
        $this->convert = $convert;
    }

    public function handleResponse(Player $player, $data): void {
        if ($data === null) {
            return;
        }
        $player->sendForm(new ConfirmForm($this->convert, $data));
    }

    public function jsonSerialize() {
        $form = [
            'type' => 'form',
            'title' => 'LoginBonus',
            'content' => '選択して下さい',
            'buttons' => []
        ];
        foreach ($this->convert->getItems() as $item) {
            $name = $item->getCustomName() === '' ? $item->getName() : $item->getCustomName();
            $form['buttons'][] = ['text' => "{$name} / {$item->getCount()}"];
        }
        return $form;
    }
}
