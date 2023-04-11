<?php

declare(strict_types = 0);

namespace lazyperson0710\miningtools\command\form;

use bbo51dog\bboform\form\SimpleForm;
use lazyperson0710\MiningLevel\MiningLevelAPI;
use lazyperson0710\miningtools\command\DiamondMiningToolCommand;
use lazyperson0710\miningtools\command\ExpansionMiningToolCommand;
use lazyperson0710\miningtools\command\NetheriteMiningToolCommand;
use lazyperson0710\miningtools\element\CommandDispatchButton;
use lazyperson0710\miningtools\element\SendFormButton;
use lazyperson0710\miningtools\EnablingSetting\SelectEnablingSettings;
use pocketmine\player\Player;

class MiningToolsForm extends SimpleForm {

	public function __construct(Player $player) {
		if (MiningLevelAPI::getInstance()->getLevel($player) >= DiamondMiningToolCommand::DiamondMiningToolsLevelLimit) {//2
			$diamondLimit = '§a' . DiamondMiningToolCommand::DiamondMiningToolsLevelLimit . 'レベルの為、開放済み';
		} else {
			$diamondLimit = '§c' . DiamondMiningToolCommand::DiamondMiningToolsLevelLimit . 'レベル以上で開放されます';
		}
		if (MiningLevelAPI::getInstance()->getLevel($player) >= NetheriteMiningToolCommand::NetheriteMiningToolsLevelLimit) {//3
			$netheriteLimit = '§a' . NetheriteMiningToolCommand::NetheriteMiningToolsLevelLimit . 'レベルの為、開放済み';
		} else {
			$netheriteLimit = '§c' . NetheriteMiningToolCommand::NetheriteMiningToolsLevelLimit . 'レベル以上で開放されます';
		}
		if (MiningLevelAPI::getInstance()->getLevel($player) >= ExpansionMiningToolCommand::ExpansionMiningToolsLevelLimit) {//4
			$expansionLimit = '§a' . ExpansionMiningToolCommand::ExpansionMiningToolsLevelLimit . 'レベルの為、開放済み';
		} else {
			$expansionLimit = '§c' . ExpansionMiningToolCommand::ExpansionMiningToolsLevelLimit . 'レベル以上で開放されます';
		}
		$this
			->setTitle('Mining Tools')
			->setText('見たいコンテンツを選択してください')
			->addElements(
				new CommandDispatchButton("Diamond Tools - 範囲採掘ツール\n{$diamondLimit}", 'dmt', null),
				new CommandDispatchButton("Netherite Tools - 範囲採掘ツール\n{$netheriteLimit}", 'nmt', null),
				new CommandDispatchButton("アップグレード - Ex範囲採掘ツール\n{$expansionLimit}", 'emt', null),
				new SendFormButton(new SelectEnablingSettings($player), '設定機能を有効化'),
			);
	}
}
