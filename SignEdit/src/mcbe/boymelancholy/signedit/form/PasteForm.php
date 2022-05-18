<?php

declare(strict_types=1);
namespace mcbe\boymelancholy\signedit\form;

use mcbe\boymelancholy\signedit\util\TextClipboard;
use pocketmine\block\BaseSign;
use pocketmine\form\Form;
use pocketmine\player\Player;

class PasteForm implements Form {

    private BaseSign $sign;
    private Player $player;

    public function __construct(BaseSign $sign, Player $player) {
        $this->sign = $sign;
        $this->player = $player;
    }

    /**
     * @inheritDoc
     */
    public function handleResponse(Player $player, $data): void {
        if ($data === null) {
            $player->sendForm(new HomeForm($this->sign));
            return;
        }
        if (is_bool($data)) {
            if ($data) {
                $player->sendForm(new HomeForm($this->sign));
            }
            return;
        }
        $signText = TextClipboard::getClipBoard($player)?->get($data);
        $this->sign->setText($signText);
        $player->getWorld()->setBlock($this->sign->getPosition(), $this->sign, true);
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize() {
        $clipboard = TextClipboard::getClipBoard($this->player);
        $formJson = [];
        $formJson['title'] = 'SignEdit　> Paste';
        if ($clipboard->size() == 0) {
            $formJson['type'] = 'modal';
            $formJson['content'] = 'Clipboard does not have any texts.';
            $formJson['button1'] = 'Back';
            $formJson['button2'] = 'Exit';
        } else {
            $formJson['type'] = 'form';
            $formJson['content'] = 'Select the text you wish to paste.';
            foreach ($clipboard->getAll() as $item) {
                $formJson['buttons'][] = [
                    'text' => implode('/', $item->getLines())
                ];
            }
        }
        return $formJson;
    }
}