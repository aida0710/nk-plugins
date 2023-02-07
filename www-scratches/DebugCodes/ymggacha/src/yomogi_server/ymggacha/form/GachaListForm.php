<?php

declare(strict_types=1);
namespace ymggacha\src\yomogi_server\ymggacha\form;

use pocketmine\form\FormValidationException;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use rarkhopper\modals\menu\element\ButtonList;
use rarkhopper\modals\menu\element\MenuFormElements;
use rarkhopper\modals\menu\MenuFormBase;
use rarkhopper\modals\menu\MenuFormResponse;
use ymggacha\src\yomogi_server\ymggacha\form\element\GachaButton;
use ymggacha\src\yomogi_server\ymggacha\gacha\GachaMap;
use ymggacha\src\yomogi_server\ymggacha\gacha\IInFormRollableGacha;
use function gettype;

class GachaListForm extends MenuFormBase {

	public function __construct() {
		parent::__construct($this->createElement());
	}

	public function createElement() : MenuFormElements {
		$buttons = new ButtonList();
		foreach (GachaMap::getAll() as $gacha) {
			if (!$gacha instanceof IInFormRollableGacha) continue;
			$buttons->append(new GachaButton($gacha));
		}
		return new MenuFormElements('ガチャ一覧', '引きたいガチャを選んでください', $buttons);
	}

	/**
	 * @throws FormValidationException
	 */
	protected function onSubmit(Player $player, MenuFormResponse $response) : void {
		$btn = $response->getPressedElement();
		if (!$btn instanceof GachaButton) {
			$player->sendMessage(TextFormat::RED . 'ガチャが見つかりませんでした');
			throw new FormValidationException('invalid response. expected a GachaButton but given ' . gettype($btn));
		}
		(new GachaForm($btn->getGacha()))->send($player);
	}
}
