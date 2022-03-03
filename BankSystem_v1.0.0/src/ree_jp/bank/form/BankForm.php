<?php

namespace ree_jp\bank\form;

use pocketmine\form\Form;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class BankForm implements Form {

    /**
     * @inheritDoc
     */
    public function handleResponse(Player $player, $data): void {
        if ($data === NULL) {
            return;
        }
        switch ($data) {
            case 0:
                $player->sendForm(new SelectForm($player));
                break;
            case 1:
                $player->sendForm(new CreateForm());
                break;
            case 2:
                $player->sendForm(new DeleteForm($player));
                break;
            default:
                $player->sendMessage(TextFormat::RED . "§bBank §7>> §cエラーが発生しました");
        }
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize() {
        return [
            'type' => 'form',
            'title' => 'BankSystem',
            'content' => "選択してください",
            'buttons' => [
                [
                    'text' => "銀行を選択する"
                ],
                [
                    'text' => "銀行を作成する"
                ],
                [
                    'text' => "銀行を削除する"
                ],
            ]
        ];
    }
}