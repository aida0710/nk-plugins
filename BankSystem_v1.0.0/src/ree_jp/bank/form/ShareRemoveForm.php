<?php

declare(strict_types = 0);

namespace ree_jp\bank\form;

use lazyperson710\core\packet\SendMessage\SendMessage;
use pocketmine\form\Form;
use pocketmine\player\Player;
use ree_jp\bank\sqlite\BankHelper;
use function strtolower;

class ShareRemoveForm implements Form {

    /** @var string */
    private $bank;

    /** @var string[] */
    private $option;

    public function __construct(string $bank) {
        $this->bank = $bank;
    }

    /**
     * @inheritDoc
     */
    public function handleResponse(Player $player, $data) : void {
        if ($data === null) return;
        $name = $this->option[$data[0]];
        if (BankHelper::getInstance()->getLeader($this->bank) === strtolower($name)) {
            SendMessage::Send($player, 'リーダーは共有を外すことが出来ません', 'Bank', false);
            return;
        }
        BankHelper::getInstance()->removeShare($this->bank, $data[0], $player->getName());
        SendMessage::Send($player, $data[0] . 'さんの共有を外しました', 'Bank', true);
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
                    'type' => 'dropdown',
                    'text' => '選択してください',
                    'options' => $option,
                ],
            ],
        ];
    }
}
