<?php

declare(strict_types = 0);
namespace deceitya\miningtools\extensions\enchant;

use bbo51dog\bboform\form\SimpleForm;
use deceitya\miningtools\element\SendFormButton;
use deceitya\miningtools\extensions\enchant\fortune\FortuneEnchantConfirmForm;
use deceitya\miningtools\extensions\enchant\unbreaking\UnbreakingEnchantConfirmForm;
use pocketmine\block\BlockLegacyIds;
use pocketmine\player\Player;

class EnchantFunctionSelectForm extends SimpleForm {

	public const CostItemId = BlockLegacyIds::PACKED_ICE;
	public const CostItemNBT = 'MiningToolsEnchantCostItem';

	public function __construct(Player $player) {
		$this
			->setTitle('Expansion Mining Tools')
			->setText('付与したい拡張機能を選択して下さい')
			->addElements(
				new SendFormButton(new UnbreakingEnchantConfirmForm($player), '耐久エンチャントを強化'),
				new SendFormButton(new FortuneEnchantConfirmForm($player), 'シルクタッチを幸運に変化'),
			);
	}

}
