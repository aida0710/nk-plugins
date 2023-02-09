<?php

declare(strict_types = 1);
namespace ree_jp\bank\form;

use lazyperson710\core\packet\SendMessage\SendMessage;
use onebone\economyapi\EconomyAPI;
use pocketmine\form\Form;
use pocketmine\player\Player;
use pocketmine\Server;
use ree_jp\bank\sqlite\BankHelper;
use function is_float;
use function is_null;
use function is_numeric;

class TransferForm implements Form {

	/** @var string */
	private $bank;

	public function __construct(string $bank) {
		$this->bank = $bank;
	}

	/**
	 * @inheritDoc
	 */
	public function handleResponse(Player $player, $data) : void {
		if ($data === null) return;
		if (!is_numeric($data[1])) {
			SendMessage::Send($player, "エラーが発生しました", "Bank", false);
			return;
		}
		if (!is_float(EconomyAPI::getInstance()->myMoney($data[0]))) {
			SendMessage::Send($player, "プレイヤーが見つかりません", "Bank", false);
			return;
		}
		if ($data[1] > BankHelper::getInstance()->getMoney($this->bank)) {
			SendMessage::Send($player, "お金が足りません", "Bank", false);
			return;
		}
		if (BankHelper::getInstance()->transferMoney($this->bank, $player->getName(), $data[1], $data[0])) {
			SendMessage::Send($player, $data[0] . "さんに" . $data[1] . "円送金しました", "Bank", false);
			$receiveP = Server::getInstance()->getOfflinePlayer($data[0]);
			if (!is_null($receiveP)) {
				SendMessage::Send($receiveP, $data[1] . "円おくられたよ", "Bank", true);
			}
		} else {
			SendMessage::Send($player, "送金出来ませんでした", "Bank", false);
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
