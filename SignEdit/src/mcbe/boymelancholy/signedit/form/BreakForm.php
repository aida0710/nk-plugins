<?php

declare(strict_types=1);
namespace mcbe\boymelancholy\signedit\form;

use mcbe\boymelancholy\signedit\util\WrittenSign;
use pocketmine\block\BaseSign;
use pocketmine\form\Form;
use pocketmine\player\Player;

class BreakForm implements Form {

    private BaseSign $sign;

    public function __construct(BaseSign $sign) {
        $this->sign = $sign;
    }

    /**
     * @inheritDoc
     */
    public function handleResponse(Player $player, $data): void {
        if (!is_bool($data)) return;
        $writtenSign = new WrittenSign($this->sign->getText());
        if ($data) {
            $writtenSign->setStandable();
        } else {
            $writtenSign->setHangable();
        }
        $player->getWorld()->dropItem($this->sign->getPosition(), $writtenSign->create());
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize() {
        return [
            'type' => 'modal',
            'title' => 'SignEdit > Break',
            'content' => 'Choose the format for dropping signs that hold text ' . " \n" . ' If you just want to break it, close this window.',
            'button1' => 'Standing',
            'button2' => 'Wall hanging'
        ];
    }
}