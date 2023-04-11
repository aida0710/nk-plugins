<?php

declare(strict_types = 1);

namespace ymggacha\src\yomogi_server\ymggacha\form\element;

use pocketmine\utils\TextFormat;
use rarkhopper\modals\menu\element\MenuFormButton;
use ymggacha\src\yomogi_server\ymggacha\gacha\IInFormRollableGacha;
use const PHP_EOL;

class GachaButton extends MenuFormButton {

	private IInFormRollableGacha $gacha;

	public function __construct(IInFormRollableGacha $gacha) {
		parent::__construct(TextFormat::BOLD . $gacha->getName() . PHP_EOL . TextFormat::RESET . TextFormat::GRAY . $gacha->getOneLineDescription());
		$this->gacha = $gacha;
	}

	public function getGacha() : IInFormRollableGacha {
		return $this->gacha;
	}
}
