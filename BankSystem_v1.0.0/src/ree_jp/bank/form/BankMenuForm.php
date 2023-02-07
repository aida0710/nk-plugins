<?php

declare(strict_types=1);
namespace ree_jp\bank\form;

use lazyperson710\core\packet\SendForm;
use lazyperson710\core\packet\SendMessage\SendMessage;
use pocketmine\form\Form;
use pocketmine\player\Player;

class BankMenuForm implements Form {

	/** @var Player */
	private $p;
	/** @var string */
	private $bank;

	public function __construct(Player $p, string $bank) {
		$this->p = $p;
		$this->bank = $bank;
	}

	/**
	 * @inheritDoc
	 */
	public function handleResponse(Player $player, $data) : void {
		if ($data === null) return;
		switch ($data) {
			case 0:
				SendForm::Send($player, (new ActionForm($this->bank, $this->p, ActionForm::BANK_PUT)));
				break;
			case 1:
				SendForm::Send($player, (new ActionForm($this->bank, $this->p, ActionForm::BANK_OUT)));
				break;
			case 2:
				SendForm::Send($player, (new ShareSelectForm($this->bank)));
				break;
			case 3:
				SendForm::Send($player, (new LogForm($this->bank)));
				break;
			default:
				SendMessage::Send($player, "エラーが発生しました", "Bank", false);
		}
	}

	/**
	 * @inheritDoc
	 */
	public function jsonSerialize() {
		return [
			'type' => 'form',
			'title' => 'BankSystem',
			'content' => "",
			'buttons' => [
				[
					'text' => "振り込み",
				],
				[
					'text' => "引き出し",
				],
				[
					'text' => "共有",
				],
				[
					'text' => "記録",
				],
			],
		];
	}
}
