<?php

namespace ree_jp\bank\form;

use onebone\economyapi\EconomyAPI;
use pocketmine\form\Form;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use ree_jp\bank\sqlite\BankHelper;

class CreateForm implements Form {

    private const MONEY = 1000;

    /**
     * @inheritDoc
     */
    public function handleResponse(Player $player, $data): void {
        if ($data === null) {
            return;
        }
        $money = EconomyAPI::getInstance()->myMoney($player);
        if ($money and $money >= self::MONEY) {
            $string = $data[0];
            $string = str_replace(["[", "]", "{", "}"], "***sqliteで使用出来ない記号です***", $string);
            if (!str_contains($string, '§')) {
                if (!BankHelper::getInstance()->isExists($string)) {
                    BankHelper::getInstance()->create($string, $player->getName());
                    EconomyAPI::getInstance()->reduceMoney($player, self::MONEY);
                    $player->sendMessage(TextFormat::GREEN . "§bBank §7>> §a銀行を作成しました");
                } else $player->sendMessage(TextFormat::RED . "§bBank §7>> §cすでにその名前の銀行が存在しています");
            } else $player->sendMessage("§bBank §7>> §c銀行名にセクションを含めることは出来ません");
        } else $player->sendMessage(TextFormat::RED . "§bBank §7>> §cお金が足りません");
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
                    "text" => "作成コストは1000円です",
                    "placeholder" => "なまけもの銀行",
                    "default" => "",
                ],
            ],
        ];
    }
}