<?php

declare(strict_types=1);
namespace ree_jp\bank\form;

use lazyperson710\core\packet\SendMessage\SendMessage;
use pocketmine\form\Form;
use pocketmine\player\Player;
use ree_jp\bank\sqlite\BankHelper;

class ShareForm implements Form {

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
		if (BankHelper::getInstance()->isShare($this->bank, $data[0])) {
			SendMessage::Send($player, "その人はすでに共有されています", "Bank", false);
			return;
		}
		BankHelper::getInstance()->share($this->bank, $data[0], $player->getName());
		SendMessage::Send($player, "{$data[0]}さんを共有しました", "Bank", true);
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
					"text" => "入力してください",
					"placeholder" => "gameTag",
					"default" => "",
				],
			],
		];
	}
}
