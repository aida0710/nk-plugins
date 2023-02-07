<?php

declare(strict_types=1);
namespace ymggacha\src\yomogi_server\ymggacha\form;

use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use rarkhopper\modals\menu\element\ButtonList;
use rarkhopper\modals\menu\element\MenuFormButton;
use rarkhopper\modals\menu\element\MenuFormElements;
use rarkhopper\modals\menu\MenuFormBase;
use rarkhopper\modals\menu\MenuFormResponse;
use ymggacha\src\yomogi_server\ymggacha\form\element\RollButton;
use ymggacha\src\yomogi_server\ymggacha\gacha\IInFormRollableGacha;
use const PHP_EOL;

class GachaForm extends MenuFormBase {

	private IInFormRollableGacha $gacha;

	public function __construct(IInFormRollableGacha $gacha) {
		$this->gacha = $gacha;
		parent::__construct($this->createElement());
	}

	public function createElement() : MenuFormElements {
		return new MenuFormElements(
			$this->gacha->getName(),
			$this->gacha->getDescription() . PHP_EOL . PHP_EOL,
			(new ButtonList())
				->append(new MenuFormButton('確率表'))
				->append(new RollButton(1, TextFormat::WHITE))
				->append(new RollButton(10, TextFormat::YELLOW))
		);
	}

	protected function onSubmit(Player $player, MenuFormResponse $response) : void {
		$btn = $response->getPressedElement();
		if (!$btn instanceof RollButton) {
			(new GachaEmmitListForm($this, $this->gacha))->send($player);
			return;
		}
		$this->gacha->roll($player, $btn->getRollNumber());
	}
}
