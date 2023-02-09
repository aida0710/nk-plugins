<?php

declare(strict_types = 1);
namespace ree_jp\bank\form;

use lazyperson710\core\packet\SendMessage\SendMessage;
use onebone\economyapi\EconomyAPI;
use pocketmine\form\Form;
use pocketmine\player\Player;
use ree_jp\bank\sqlite\BankHelper;
use function is_numeric;
use function mb_strpos;

class ActionForm implements Form {

	public const BANK_PUT = 1;
	public const BANK_OUT = 2;

	/** @var string */
	private $bank;
	/** @var int */
	private $type;
	/** @var Player */
	private $p;

	public function __construct(string $bank, Player $p, int $type) {
		$this->bank = $bank;
		$this->p = $p;
		$this->type = $type;
	}

	/**
	 * @inheritDoc
	 */
	public function handleResponse(Player $player, $data) : void {
		if ($data === null) return;
		if (!is_numeric($data[0]) || $data[0] <= 0) {
			SendMessage::Send($player, 'エラーが発生しました', 'Bank', false);
			return;
		}
		switch ($this->type) {
			case self::BANK_PUT:
				if ($data[0] > EconomyAPI::getInstance()->myMoney($player)) {
					SendMessage::Send($player, 'お金が足りません', 'Bank', false);
					return;
				}
				if (mb_strpos($data[0], '.')) {
					SendMessage::Send($player, '振り込む金額に小数点を含めることはできません', 'Bank', false);
					return;
				}
				EconomyAPI::getInstance()->reduceMoney($player, $data[0]);
				BankHelper::getInstance()->addMoney($this->bank, $player->getName(), $data[0]);
				SendMessage::Send($player, $data[0] . '円振り込みました', 'Bank', true);
				return;
			case self::BANK_OUT:
				if ($data[0] > BankHelper::getInstance()->getMoney($this->bank)) {
					SendMessage::Send($player, 'お金が足りません', 'Bank', false);
					return;
				}
				if (mb_strpos($data[0], '.')) {
					SendMessage::Send($player, '引き出す金額に小数点を含めることはできません', 'Bank', false);
					return;
				}
				BankHelper::getInstance()->removeMoney($this->bank, $player->getName(), $data[0]);
				EconomyAPI::getInstance()->addMoney($player, $data[0]);
				SendMessage::Send($player, $data[0] . '円引き出しました', 'Bank', true);
				return;
			default:
				SendMessage::Send($player, 'エラーが発生しました', 'Bank', false);
		}
	}

	/**
	 * @inheritDoc
	 */
	public function jsonSerialize() {
		$text = "\n現在の所持金 : " . EconomyAPI::getInstance()->myMoney($this->p) . "\n銀行に入ってるお金 : " . BankHelper::getInstance()->getMoney($this->bank);
		switch ($this->type) {
			case self::BANK_PUT:
				$text = '振り込む金額を入力してください' . $text;
				break;
			case self::BANK_OUT:
				$text = '引き出す金額を入力してください' . $text;
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
					'type' => 'input',
					'text' => $text,
					'placeholder' => '金額',
					'default' => '',
				],
			],
		];
	}
}
