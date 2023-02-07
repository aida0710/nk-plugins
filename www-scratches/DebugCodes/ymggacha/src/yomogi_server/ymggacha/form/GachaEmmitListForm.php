<?php

declare(strict_types=1);
namespace ymggacha\src\yomogi_server\ymggacha\form;

use pocketmine\player\Player;
use rarkhopper\modals\menu\element\ButtonList;
use rarkhopper\modals\menu\element\MenuFormButton;
use rarkhopper\modals\menu\element\MenuFormElements;
use rarkhopper\modals\menu\MenuFormBase;
use rarkhopper\modals\menu\MenuFormResponse;
use ymggacha\src\yomogi_server\ymggacha\gacha\IInFormRollableGacha;

class GachaEmmitListForm extends MenuFormBase {

	private MenuFormBase $before;

	public function __construct(MenuFormBase $before, IInFormRollableGacha $gacha) {
		parent::__construct($this->createElement($gacha));
		$this->before = $before;
	}

	public function createElement(IInFormRollableGacha $gacha) : MenuFormElements {
		return new MenuFormElements(
			'よもぎガチャ排出確率一覧表',
			$gacha->getEmmitList(),
			(new ButtonList())->append(new MenuFormButton('閉じる'))
		);
	}

	protected function onSubmit(Player $player, MenuFormResponse $response) : void {
		$this->before->send($player);
	}
}
