<?php

namespace lazyperson0710\EffectItems;

use lazyperson0710\EffectItems\event\AirBlock;
use lazyperson0710\EffectItems\event\BlastFurnacePickaxe;
use lazyperson0710\EffectItems\event\DamageEventListener;
use lazyperson0710\EffectItems\event\ExplosionTools;
use lazyperson0710\EffectItems\event\GlowstoneBreaker;
use lazyperson0710\EffectItems\event\ObsidianBreaker;
use lazyperson0710\EffectItems\event\OldTools;
use lazyperson0710\EffectItems\task\RepetitionTask;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase {

    public function onEnable(): void {
        $manager = $this->getServer()->getPluginManager();
        $manager->registerEvents(new AirBlock(), $this);
        $manager->registerEvents(new BlastFurnacePickaxe(), $this);
        $manager->registerEvents(new DamageEventListener(), $this);
        $manager->registerEvents(new ExplosionTools(), $this);
        $manager->registerEvents(new GlowstoneBreaker(), $this);
        $manager->registerEvents(new ObsidianBreaker(), $this);
        $manager->registerEvents(new OldTools(), $this);
        $this->getScheduler()->scheduleRepeatingTask(new RepetitionTask(), 10);
    }
}
