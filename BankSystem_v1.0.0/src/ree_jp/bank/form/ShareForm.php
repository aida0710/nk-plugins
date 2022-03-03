<?php

namespace ree_jp\bank\form;

use pocketmine\form\Form;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use ree_jp\bank\sqlite\BankHelper;

class ShareForm implements Form {

    /**
     * @var string
     */
    private $bank;

    public function __construct(string $bank) {
        $this->bank = $bank;
    }

    /**
     * @inheritDoc
     */
    public function handleResponse(Player $player, $data): void {
        if ($data === null) return;
        if (BankHelper::getInstance()->isShare($this->bank, $data[0])) {
            $player->sendMessage(TextFormat::RED . "§bBank §7>> §cその人はすでに共有されています");
            return;
        }
        BankHelper::getInstance()->share($this->bank, $data[0], $player->getName());
        $player->sendMessage(TextFormat::GREEN . "§bBank §7>> §a{$data[0]}さんを共有しました");
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize() {
        return [
            'type' => 'custom_form',
            'title' => 'BankSystem',
            'content' => [
                [
                    "type" => "input",
                    "text" => "入力してください",
                    "placeholder" => "gameTag",
                    "default" => "",
                ],
            ]
        ];
    }
}