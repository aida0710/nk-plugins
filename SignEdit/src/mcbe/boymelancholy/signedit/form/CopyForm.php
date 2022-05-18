<?php

declare(strict_types=1);
namespace mcbe\boymelancholy\signedit\form;

use mcbe\boymelancholy\signedit\util\TextClipboard;
use pocketmine\block\BaseSign;
use pocketmine\form\Form;
use pocketmine\player\Player;

class CopyForm implements Form {

    private BaseSign $sign;

    public function __construct(BaseSign $sign) {
        $this->sign = $sign;
    }

    /**
     * @inheritDoc
     */
    public function handleResponse(Player $player, $data): void {
        if (!(bool)$data) {
            $player->sendForm(new HomeForm($this->sign));
            return;
        }
        $signText = $this->sign->getText();
        $clipboard = TextClipboard::getClipBoard($player);
        $clipboard->add($signText);
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize() {
        return [
            'type' => 'modal',
            'title' => 'SignEdit > Copy',
            'content' => 'Do you want to copy the text on this sign?',
            'button1' => 'Yes',
            'button2' => 'No'
        ];
    }
}