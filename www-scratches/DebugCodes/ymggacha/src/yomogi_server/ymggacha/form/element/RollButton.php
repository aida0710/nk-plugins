<?php

declare(strict_types = 1);
namespace ymggacha\src\yomogi_server\ymggacha\form\element;

use pocketmine\utils\TextFormat;
use rarkhopper\modals\menu\element\MenuFormButton;

class RollButton extends MenuFormButton {

	private int $rollCnt;

	/**
	 * @param string $color {@see TextFormat}
	 */
	public function __construct(int $rollCnt, string $color) {
		parent::__construct(TextFormat::BOLD . $color . $rollCnt . TextFormat::RESET . TextFormat::GRAY . '回引く');
		$this->rollCnt = $rollCnt;
	}

	public function getRollNumber() : int {
		return $this->rollCnt;
	}
}
