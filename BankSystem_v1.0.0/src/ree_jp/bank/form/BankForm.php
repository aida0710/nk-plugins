<?php

namespace ree_jp\bank\form;

use lazyperson710\core\packet\SendForm;
use pocketmine\form\Form;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class BankForm implements Form {

    /**
     * @inheritDoc
     */
    public function handleResponse(Player $player, $data): void {
        if ($data === null) {
            return;
        }
        switch ($data) {
            case 0:
                SendForm::Send($player, (new SelectForm($player)));
                break;
            case 1:
                SendForm::Send($player, (new CreateForm()));
                break;
            case 2:
                SendForm::Send($player, (new DeleteForm($player)));
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
                    'text' => "銀行を選択する",
                ],
                [
                    'text' => "銀行を作成する",
                ],
                [
                    'text' => "銀行を削除する",
                ],
            ],
        ];
    }
}