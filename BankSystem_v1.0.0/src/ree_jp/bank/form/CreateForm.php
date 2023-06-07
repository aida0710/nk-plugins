<?php

declare(strict_types = 0);

namespace ree_jp\bank\form;

use lazyperson710\core\packet\SendMessage\SendMessage;
use onebone\economyapi\EconomyAPI;
use pocketmine\form\Form;
use pocketmine\player\Player;
use ree_jp\bank\sqlite\BankHelper;
use function str_contains;
use function str_replace;

class CreateForm implements Form {

    private const MONEY = 1000;

    /**
     * @inheritDoc
     */
    public function handleResponse(Player $player, $data) : void {
        if ($data === null) {
            return;
        }
        $money = EconomyAPI::getInstance()->myMoney($player);
        if ($money && $money >= self::MONEY) {
            $string = $data[0];
            $string = str_replace(['[', ']', '{', '}'], '***sqliteで使用出来ない記号です***', $string);
            if (!str_contains($string, '§')) {
                if (!BankHelper::getInstance()->isExists($string)) {
                    BankHelper::getInstance()->create($string, $player->getName());
                    EconomyAPI::getInstance()->reduceMoney($player, self::MONEY);
                    SendMessage::Send($player, '銀行を作成しました', 'Bank', true);
                } else {
                    SendMessage::Send($player, 'すでにその名前の銀行が存在しています', 'Bank', false);
                }
            } else {
                SendMessage::Send($player, '銀行名にセクションを含めることは出来ません', 'Bank', false);
            }
        } else {
            SendMessage::Send($player, 'お金が足りません', 'Bank', false);
        }
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize() : array {
        return [
            'type' => 'custom_form',
            'title' => 'BankSystem',
            'content' => [
                [
                    'type' => 'input',
                    'text' => '作成コストは1000円です',
                    'placeholder' => 'なまけもの銀行',
                    'default' => '',
                ],
            ],
        ];
    }
}
