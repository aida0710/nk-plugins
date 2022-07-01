<?php

namespace ree_jp\bank\form;

use onebone\economyapi\EconomyAPI;
use pocketmine\form\Form;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use ree_jp\bank\sqlite\BankHelper;

class ActionForm implements Form {

    public const BANK_PUT = 1;
    public const BANK_OUT = 2;

    /**
     * @var string
     */
    private $bank;
    /**
     * @var int
     */
    private $type;
    /**
     * @var Player
     */
    private $p;

    public function __construct(string $bank, Player $p, int $type) {
        $this->bank = $bank;
        $this->p = $p;
        $this->type = $type;
    }

    /**
     * @inheritDoc
     */
    public function handleResponse(Player $player, $data): void {
        if ($data === null) return;
        if (!is_numeric($data[0]) or $data[0] <= 0) {
            $player->sendMessage("§bBank §7>> §cエラーが発生しました");
            return;
        }
        switch ($this->type) {
            case self::BANK_PUT:
                if ($data[0] > EconomyAPI::getInstance()->myMoney($player)) {
                    $player->sendMessage(TextFormat::RED . "§bBank §7>> §cお金が足りません");
                    return;
                }
                EconomyAPI::getInstance()->reduceMoney($player, $data[0]);
                BankHelper::getInstance()->addMoney($this->bank, $player->getName(), $data[0]);
                $player->sendMessage(TextFormat::GREEN . "§bBank §7>> §a" . $data[0] . "円振り込みました");
                return;
            case self::BANK_OUT:
                if ($data[0] > BankHelper::getInstance()->getMoney($this->bank)) {
                    $player->sendMessage(TextFormat::RED . "§bBank §7>> §cお金が足りません");
                    return;
                }
                BankHelper::getInstance()->removeMoney($this->bank, $player->getName(), $data[0]);
                EconomyAPI::getInstance()->addMoney($player, $data[0]);
                $player->sendMessage(TextFormat::GREEN . "§bBank §7>> §a" . $data[0] . "円引き出しました");
                return;
            default:
                $player->sendMessage(TextFormat::RED . "§bBank §7>> §cエラーが発生しました");
        }
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize() {
        $text = "\n現在の所持金 : " . EconomyAPI::getInstance()->myMoney($this->p) . "\n銀行に入ってるお金 : " . BankHelper::getInstance()->getMoney($this->bank);
        switch ($this->type) {
            case self::BANK_PUT:
                $text = "振り込む金額を入力してください" . $text;
                break;
            case self::BANK_OUT:
                $text = "引き出す金額を入力してください" . $text;
                break;
            default:
                return [
                    'type' => 'form',
                    'title' => 'BankSystem',
                    'content' => 'エラーが発生しました',
                    'buttons' => [],
                ];
        }
        return [
            'type' => 'custom_form',
            'title' => 'BankSystem',
            'content' => [
                [
                    "type" => "input",
                    "text" => $text,
                    "placeholder" => "金額",
                    "default" => "",
                ],
            ]
        ];
    }
}