<?php

namespace lazyperson0710\EffectItems\event;

use lazyperson0710\EffectItems\items\BlastFurnacePickaxe;
use lazyperson0710\EffectItems\items\GlowstoneBreaker;
use lazyperson0710\EffectItems\items\ObsidianBreaker;
use lazyperson0710\EffectItems\items\OldTools;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;

class PlayerBlockBreakEvent implements Listener {

    public function onBreak(BlockBreakEvent $event) {
        if ($event->isCancelled()) return;
        $player = $event->getPlayer();
        $NamedTag = $player->getInventory()->getItemInHand()->getNamedTag();
        if ($NamedTag->getTag('BlastFurnacePickaxe') !== null) BlastFurnacePickaxe::init($event);
        if ($NamedTag->getTag('GlowstoneBreaker') !== null) GlowstoneBreaker::init($event);
        if ($NamedTag->getTag('ObsidianBreaker') !== null) ObsidianBreaker::init($event);
        if ($NamedTag->getTag('OldTools') !== null) OldTools::init($event);
    }
}
