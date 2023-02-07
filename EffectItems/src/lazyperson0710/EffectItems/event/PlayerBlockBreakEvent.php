<?php

declare(strict_types=1);
namespace lazyperson0710\EffectItems\event;

use lazyperson0710\EffectItems\items\breakListener\BlastFurnacePickaxe;
use lazyperson0710\EffectItems\items\breakListener\GlowstoneBreaker;
use lazyperson0710\EffectItems\items\breakListener\ObsidianBreaker;
use lazyperson0710\EffectItems\items\breakListener\OldTools;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;

class PlayerBlockBreakEvent implements Listener {

	public function onBreak(BlockBreakEvent $event) {
		if ($event->isCancelled()) return;
		$player = $event->getPlayer();
		$NamedTag = $player->getInventory()->getItemInHand()->getNamedTag();
		if ($NamedTag->getTag('BlastFurnacePickaxe') !== null) BlastFurnacePickaxe::execution($event);
		if ($NamedTag->getTag('GlowstoneBreaker') !== null) GlowstoneBreaker::execution($event);
		if ($NamedTag->getTag('ObsidianBreaker') !== null) ObsidianBreaker::execution($event);
		if ($NamedTag->getTag('OldTools') !== null) OldTools::execution($event);
	}
}
