<?php

namespace ree_jp\bank\form;

use pocketmine\form\Form;
use pocketmine\player\Player;
use ree_jp\bank\sqlite\BankHelper;

class RankingForm implements Form {

    /**
     * @inheritDoc
     */
    public function handleResponse(Player $player, $data): void {
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize() {
        $string = "";
        $rank = 1;
        foreach (BankHelper::getInstance()->getBankDate() as $bank) {
            $string = $string . "§r[" . $rank . "位] §r" . $bank["bank"] . "§r | §r" . $bank["money"] . "円§r | §r作成者" . $bank["leader"] . "\n";
            $rank++;
        }
        return [
            'type' => 'form',
            'title' => 'BankSystem',
            'content' => $string,
            'buttons' => [],
        ];
    }
}