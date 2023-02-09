<?php

declare(strict_types = 1);
namespace lazyperson0710\PlayerSetting\form\element;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\element\ButtonImage;
use Deceitya\MiningLevel\MiningLevelAPI;
use lazyperson0710\PlayerSetting\form\SelectSettingForm;
use lazyperson710\core\packet\SendForm;
use lazyperson710\core\packet\SoundPacket;
use pocketmine\form\Form;
use pocketmine\player\Player;

class SendMiningToolsSettingFormButton extends Button {

	private Form $form;
	private Player $player;

	public function __construct(Player $player, Form $form, string $text, ?ButtonImage $image = null) {
		parent::__construct($text, $image);
		$this->player = $player;
		$this->form = $form;
	}

	public function handleSubmit(Player $player) : void {
		if (MiningLevelAPI::getInstance()->getLevel($this->player) >= SelectSettingForm::LevelLimit) {
			SendForm::Send($player, $this->form);
		} else {
			SendForm::Send($player, new SelectSettingForm($this->player, "\n§c要求されたレベルに達していない為処理が中断されました\n要求レベル -> lv. " . SelectSettingForm::LevelLimit));
			SoundPacket::Send($player, 'note.bass');
		}
	}
}
