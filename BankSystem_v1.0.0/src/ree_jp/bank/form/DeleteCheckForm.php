<?php

declare(strict_types = 0);

namespace ree_jp\bank\form;

use lazyperson710\core\packet\SendForm;
use lazyperson710\core\packet\SendMessage\SendMessage;
use pocketmine\form\Form;
use pocketmine\player\Player;
use ree_jp\bank\sqlite\BankHelper;

class DeleteCheckForm implements Form {

    /** @var string */
    private $bank;

    public function __construct(string $bank) {
        $this->bank = $bank;
    }

    /**
     * @inheritDoc
     */
    public function handleResponse(Player $player, $data) : void {
        if ($data === null) {
            return;
        }
        if ($data) {
            BankHelper::getInstance()->remove($this->bank);
            SendMessage::Send($player, '削除しました', 'Bank', true);
        } else SendForm::Send($player, (new BankForm()));
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize() : array {
        return [
            'type' => 'modal',
            'title' => 'BankSystem',
            'content' => $this->bank . 'を本当に削除しますか?',
            'button1' => 'はい',
            'button2' => 'いいえ',
        ];
    }
}
