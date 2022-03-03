<?php

namespace ree_jp\bank\form;

use pocketmine\form\Form;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use ree_jp\bank\sqlite\BankHelper;

class ShareRemoveForm implements Form {

    /**
     * @var string
     */
    private $bank;

    /**
     * @var string[]
     */
    private $option;

    public function __construct(string $bank) {
        $this->bank = $bank;
    }

    /**
     * @inheritDoc
     */
    public function handleResponse(Player $player, $data): void {
        if ($data === null) return;
        $name = $this->option[$data[0]];
        if (BankHelper::getInstance()->getLeader($this->bank) === strtolower($name)) {
            $player->sendMessage(TextFormat::RED . "§bBank §7>> §cリーダーは共有を外すことが出来ません");
            return;
        }
        BankHelper::getInstance()->removeShare($this->bank, $data[0], $player->getName());
        $player->sendMessage(TextFormat::GREEN . "§bBank §7>> §a" . $data[0] . "さんの共有を外しました");
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize() {
        $option = [];
        foreach (BankHelper::getInstance()->getAllShare($this->bank) as $member) $option[] = $member;
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
            ]
        ];
    }
}