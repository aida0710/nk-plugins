<?php

declare(strict_types = 0);
namespace nkserver\ranking\form;

use Deceitya\MiningLevel\Form\RankForm as MiningLevelRankinkForm;
use lazyperson710\core\packet\SendForm;
use nkserver\ranking\libs\form\SimpleForm;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use ree_jp\bank\form\RankingForm as BankRankingForm;

class HomeForm extends SimpleForm {

	public function __construct() {
		$this->title = TextFormat::BOLD . 'ホーム';
		$this->addButton('統計情報を見る');
		$this->addButton('銀行預金ランキング');
		$this->addButton('マイニングレベルランキング');
		$this->submit = function (Player $player, int $data) : void {
			SendForm::Send($player, (
			match ($data) {
				0 => new StatisticsForm($this, $player->getName()),
				1 => new BankRankingForm,
				2 => new MiningLevelRankinkForm
			}
			));
		};
	}
}