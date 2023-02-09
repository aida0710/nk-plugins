<?php

declare(strict_types = 1);
namespace lazyperson710\sff\form;

use bbo51dog\bboform\form\SimpleForm;
use lazyperson710\sff\element\CommandDispatchButton;
use lazyperson710\sff\element\SendFormButton;
use lazyperson710\sff\form\land\LandGiveForm;
use lazyperson710\sff\form\land\LandInviteForm;
use lazyperson710\sff\form\land\LandPositionForm;
use lazyperson710\sff\form\land\LandWarpForm;
use pocketmine\player\Player;

class LandForm extends SimpleForm {

	public function __construct(Player $player) {
		$this
			->setTitle("Land Command")
			->addElements(
				new SendFormButton(new LandPositionForm($player), "土地の範囲を設定\n土地の購入、売却など", null),
				new SendFormButton(new LandWarpForm(), "他の土地にワープ", null),
				new SendFormButton(new LandInviteForm($player), "土地の共有関係", null),
				new SendFormButton(new LandGiveForm(), "土地を譲渡", null),
				new CommandDispatchButton("自分の所持している土地リスト", "land whose", null),
				new CommandDispatchButton("現在いる地点の土地所有者と土地番号を表示", "land here", null),
			);
	}
}
