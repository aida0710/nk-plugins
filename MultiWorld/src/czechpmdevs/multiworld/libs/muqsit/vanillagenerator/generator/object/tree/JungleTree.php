<?php

declare(strict_types = 0);
namespace czechpmdevs\multiworld\libs\muqsit\vanillagenerator\generator\object\tree;

use pocketmine\block\utils\TreeType;
use pocketmine\utils\Random;
use pocketmine\world\BlockTransaction;

class JungleTree extends GenericTree {

	/**
	 * Initializes this tree with a random height, preparing it to attempt to generate.
	 */
	public function __construct(Random $random, BlockTransaction $transaction) {
		parent::__construct($random, $transaction);
		$this->setHeight($random->nextBoundedInt(7) + 4);
		$this->setType(TreeType::JUNGLE());
	}
}
