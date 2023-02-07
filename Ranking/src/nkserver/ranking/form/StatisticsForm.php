<?php

declare(strict_types=1);
namespace nkserver\ranking\form;

use lazyperson710\core\packet\SendForm;
use pocketmine\form\Form;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class StatisticsForm extends BackableForm {

	public function __construct(?Form $before, string $receiver) {
		parent::__construct($before);
		$this->title = 'ホーム/' . TextFormat::BOLD . '統計情報を閲覧';
		$this->addButton('破壊したブロック'); //send BlockStatisticsForm
		$this->addButton('設置したブロック');
		$this->addButton('その他の統計'); //send OtherStatisticsForm
		$this->addButton('戻る');
		$this->submit = function (Player $player, int $data) use ($receiver) : void {
			$form = match ($data) {
				0 => new BlockBreakStatisticsForm($this, $receiver),
				1 => new BlockPlaceStatisticsForm($this, $receiver),
				2 => new OtherStatisticsForm($this, $receiver),
				3 => $this->before
			};
			if ($form === null) return;
			SendForm::Send($player, ($form));
		};
	}
}
