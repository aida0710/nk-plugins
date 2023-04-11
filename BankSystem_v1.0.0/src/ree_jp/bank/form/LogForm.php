<?php

declare(strict_types = 0);

namespace ree_jp\bank\form;

use pocketmine\form\Form;
use pocketmine\player\Player;
use ree_jp\bank\sqlite\LogHelper;
use function array_reverse;

class LogForm implements Form {

	/** @var string */
	private $bank;

	public function __construct(string $bank) {
		$this->bank = $bank;
	}

	/**
	 * @inheritDoc
	 */
	public function handleResponse(Player $player, $data) : void {
	}

	/**
	 * @inheritDoc
	 */
	public function jsonSerialize() {
		$logs = '';
		$reverse_log = array_reverse(LogHelper::getInstance()->getLog($this->bank)->logs);
		foreach ($reverse_log as $log) $logs = $logs . $log . "\n";
		return [
			'type' => 'form',
			'title' => 'BankSystem',
			'content' => $logs,
			'buttons' => [],
		];
	}
}
