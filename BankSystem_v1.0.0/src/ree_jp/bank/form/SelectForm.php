<?php

declare(strict_types=1);
namespace ree_jp\bank\form;

use lazyperson710\core\packet\SendForm;
use lazyperson710\core\packet\SendMessage\SendMessage;
use pocketmine\form\Form;
use pocketmine\player\Player;
use ree_jp\bank\sqlite\BankHelper;

class SelectForm implements Form {

	/** @var Player */
	private $p;

	/** @var string[] */
	private $option;

	public function __construct(Player $p) {
		$this->p = $p;
	}

	/**
	 * @inheritDoc
	 */
	public function handleResponse(Player $player, $data) : void {
		if ($data === null) {
			return;
		}
		if (isset($this->option[$data[0]])) {
			SendForm::Send($player, (new BankMenuForm($player, $this->option[$data[0]])));
		} else {
			SendMessage::Send($player, "エラーが発生しました", "Bank", false);
		}
	}

	/**
	 * @inheritDoc
	 */
	public function jsonSerialize() {
		$option = [];
		foreach (BankHelper::getInstance()->getAllBank($this->p->getName()) as $bank) $option[] = $bank;
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
			],
		];
	}
}
