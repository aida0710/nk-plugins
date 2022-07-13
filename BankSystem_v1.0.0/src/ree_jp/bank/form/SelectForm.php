<?php

namespace ree_jp\bank\form;

use pocketmine\form\Form;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use ree_jp\bank\sqlite\BankHelper;

class SelectForm implements Form {

    /**
     * @var Player
     */
    private $p;

    /**
     * @var string[]
     */
    private $option;

    public function __construct(Player $p) {
        $this->p = $p;
    }

    /**
     * @inheritDoc
     */
    public function handleResponse(Player $player, $data): void {
        if ($data === null) {
            return;
        }
        if (isset($this->option[$data[0]])) {
            $player->sendForm(new BankMenuForm($player, $this->option[$data[0]]));
        } else $player->sendMessage(TextFormat::RED . "§bBank §7>> §cエラーが発生しました");
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize() {
        $option = [];
        foreach (BankHelper::getInstance()->getAllBank($this->p->getName()) as $bank) $option[] = $bank;
        $this->option = $option;
        return [
            'type' => 'custom_form',
            'title' => 'BankSystem',
            'content' => [
                [
                    "type" => "dropdown",
                    "text" => "選択してください",
                    "options" => $option,
                ],
            ],
        ];
    }
}