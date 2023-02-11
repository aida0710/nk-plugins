<?php

declare(strict_types = 0);
namespace lazyperson0710\PlayerSetting\form;

use bbo51dog\bboform\form\SimpleForm;
use Deceitya\MiningLevel\MiningLevelAPI;
use lazyperson0710\PlayerSetting\form\element\SendMiningToolsSettingFormButton;
use lazyperson0710\PlayerSetting\form\element\SendNormalSettingFormButton;
use pocketmine\player\Player;

class SelectSettingForm extends SimpleForm {

	public const LevelLimit = 280;

	public function __construct(Player $player, ?string $text = null) {
		if (MiningLevelAPI::getInstance()->getLevel($player) >= SelectSettingForm::LevelLimit) {
			$levelMessage = '§a解放済み / 要求レベル -> lv. ' . SelectSettingForm::LevelLimit;
		} else {
			$levelMessage = '§c ' . SelectSettingForm::LevelLimit . 'レベル以上で開放されます';
		}
		$this
			->setTitle('PlayerSettings')
			->setText("設定したい項目を選択してください{$text}")
			->addElements(
				new SendNormalSettingFormButton(new NormalSettingListForm($player), 'Normal Setting'),
				new SendMiningToolsSettingFormButton($player, new MiningToolsSettingListForm($player), "Mining Tools Setting\n{$levelMessage}"),
			);
	}
}
