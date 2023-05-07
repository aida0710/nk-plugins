<?php

declare(strict_types = 0);

namespace lazyperson710\core\override;

use lazyperson710\core\override\block\RedStoneOreBlock;
use lazyperson710\core\override\block\VanillaSponge;
use pocketmine\block\BlockBreakInfo as BreakInfo;
use pocketmine\block\BlockFactory;
use pocketmine\block\BlockIdentifierFlattened as BIDFlattened;
use pocketmine\block\BlockLegacyIds as Ids;
use pocketmine\block\BlockToolType as ToolType;
use pocketmine\item\ToolTier;

class Override {

	public function init() : void {
		BlockFactory::getInstance()->register(
			new RedStoneOreBlock(
				new BIDFlattened(
					Ids::REDSTONE_ORE,
					[Ids::LIT_REDSTONE_ORE],
					0
				),
				'Redstone Ore',
				new BreakInfo(
					3.0,
					ToolType::PICKAXE,
					ToolTier::IRON()->getHarvestLevel())
			), true);
		BlockFactory::getInstance()->register(new VanillaSponge(), true);
	}

}