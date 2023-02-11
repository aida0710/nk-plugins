<?php

declare(strict_types = 0);
namespace ree_jp\bank\form;

use lazyperson710\core\packet\SendForm;
use pocketmine\form\Form;
use pocketmine\player\Player;

class ShareSelectForm implements Form {

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
		if ($data) {
			SendForm::Send($player, (new ShareForm($this->bank)));
		} else SendForm::Send($player, (new ShareRemoveForm($this->bank)));
	}

	/**
	 * @inheritDoc
	 */
	public function jsonSerialize() {
		return [
			'type' => 'modal',
			'title' => 'BankSystem',
			'content' => '選択してください',
			'button1' => '共有する',
			'button2' => '共有を解除する',
		];
	}
}
