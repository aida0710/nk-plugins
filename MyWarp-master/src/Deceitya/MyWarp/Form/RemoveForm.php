<?php

namespace Deceitya\MyWarp\Form;

use Deceitya\MyWarp\Database;
use lazyperson710\core\packet\SendForm;
use pocketmine\form\Form;
use pocketmine\player\Player;

class RemoveForm implements Form {

    private array $warps = [];

    public function __construct(Player $player) {
        foreach (Database::getInstance()->getWarpPositions($player) as $warp) {
            $this->warps[] = $warp['name'];
        }
    }

    public function handleResponse(Player $player, $data): void {
        if ($data === null) {
            return;
        }
        SendForm::Send($player, (new ConfirmForm($player, $this->warps[$data])));
    }

    public function jsonSerialize() {
        $form = [
            'type' => 'form',
            'title' => 'MyWarp',
            'content' => '',
            'buttons' => [],
        ];
        foreach ($this->warps as $pos) {
            $form['buttons'][] = ['text' => $pos];
        }
        return $form;
    }
}