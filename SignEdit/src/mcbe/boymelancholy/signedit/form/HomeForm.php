<?php

declare(strict_types=1);
namespace mcbe\boymelancholy\signedit\form;

use pocketmine\block\BaseSign;
use pocketmine\form\Form;
use pocketmine\player\Player;

class HomeForm implements Form {

    private BaseSign $sign;

    public function __construct(BaseSign $sign) {
        $this->sign = $sign;
    }

    /**
     * @inheritDoc
     */
    public function handleResponse(Player $player, $data): void {
        if ($data === null) {
            return;
        }
        switch ((int)$data) {
            case 0:
                $player->sendForm(new EditForm($this->sign));
                break;
            case 1:
                $player->sendForm(new CopyForm($this->sign));
                break;
            case 2:
                $player->sendForm(new PasteForm($this->sign, $player));
                break;
            case 3:
                $player->sendForm(new ClearForm($this->sign));
                break;
        }
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize() {
        return [
            'type' => 'form',
            'title' => 'SignEdit',
            'content' => 'Please select the process you wish to execute.',
            'buttons' => [
                [
                    'text' => 'Edit'
                ],
                [
                    'text' => 'Copy'
                ],
                [
                    'text' => 'Paste'
                ],
                [
                    'text' => 'Erase'
                ]
            ]
        ];
    }
}