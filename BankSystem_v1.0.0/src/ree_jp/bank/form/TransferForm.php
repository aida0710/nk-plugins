<?php

namespace ree_jp\bank\form;

use lazyperson710\core\packet\SoundPacket;
use onebone\economyapi\EconomyAPI;
use pocketmine\form\Form;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use ree_jp\bank\sqlite\BankHelper;

class TransferForm implements Form {

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
        if (!is_numeric($data[1])) {
            $player->sendMessage(TextFormat::RED . "§bBank §7>> §cエラーが発生しました");
            SoundPacket::Send($player, 'note.bass');
            return;
        }
        if (!is_float(EconomyAPI::getInstance()->myMoney($data[0]))) {
            $player->sendMessage(TextFormat::RED . "§bBank §7>> §cプレイヤーが見つかりません");
            SoundPacket::Send($player, 'note.bass');
            return;
        }
        if ($data[1] > BankHelper::getInstance()->getMoney($this->bank)) {
            $player->sendMessage(TextFormat::RED . "§bBank §7>> §cお金が足りません");
            SoundPacket::Send($player, 'note.bass');
            return;
        }
        if (BankHelper::getInstance()->transferMoney($this->bank, $player->getName(), $data[1], $data[0])) {
            $player->sendMessage(TextFormat::GREEN . "§bBank §7>> §a" . $data[0] . "さんに" . $data[1] . "円送金しました");
            SoundPacket::Send($player, 'note.harp');
            $receiveP = Server::getInstance()->getOfflinePlayer($data[0]);
            if (!is_null($receiveP)) {
                $receiveP->sendMessage($data[1] . "円おくられたよ");
                SoundPacket::Send($player, 'note.harp');
            }
        } else {
            $player->sendMessage(TextFormat::RED . "§bBank §7>> §c送金出来ませんでした");
            SoundPacket::Send($player, 'note.bass');
        }
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
                    "text" => "送金する方を入力してください",
                    "placeholder" => "プレイヤーの名前",
                    "default" => "",
                ],
                [
                    "type" => "input",
                    "text" => "金額を入力してください\n銀行に入ってるお金 : " . BankHelper::getInstance()->getMoney($this->bank),
                    "placeholder" => "金額",
                    "default" => "",
                ],
            ],
        ];
    }
}
