<?php

namespace ree_jp\bank\form;

use pocketmine\form\Form;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class BankMenuForm implements Form {

    /**
     * @var Player
     */
    private $p;
    /**
     * @var string
     */
    private $bank;

    public function __construct(Player $p, string $bank) {
        $this->p = $p;
        $this->bank = $bank;
    }

    /**
     * @inheritDoc
     */
    public function handleResponse(Player $player, $data): void {
        if ($data === null) return;
        switch ($data) {
            case 0:
                $player->sendForm(new ActionForm($this->bank, $this->p, ActionForm::BANK_PUT));
                break;
            case 1:
                $player->sendForm(new ActionForm($this->bank, $this->p, ActionForm::BANK_OUT));
                break;
            case 2:
                $player->sendForm(new ShareSelectForm($this->bank));
                break;
            case 3:
                $player->sendForm(new LogForm($this->bank));
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
            'content' => "",
            'buttons' => [
                [
                    'text' => "振り込み",
                ],
                [
                    'text' => "引き出し",
                ],
                [
                    'text' => "共有",
                ],
                [
                    'text' => "記録",
                ],
            ],
        ];
    }
}