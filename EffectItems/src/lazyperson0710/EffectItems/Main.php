<?php

namespace lazyperson0710\EffectItems;

use lazyperson0710\EffectItems\tools\pickaxe\GlowstoneBreaker;
use lazyperson0710\EffectItems\tools\pickaxe\ObsidianBreaker;
use lazyperson0710\EffectItems\tools\pickaxe\OldPickaxe;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase {

    public function onEnable(): void {
        //$this->getServer()->getPluginManager()->registerEvents(new DamageEventListener(), $this);
        //$this->getServer()->getPluginManager()->registerEvents(new InteractEventListener(), $this);
        $manager = $this->getServer()->getPluginManager();
        $manager->registerEvents(new GlowstoneBreaker(), $this);
        $manager->registerEvents(new ObsidianBreaker(), $this);
        $manager->registerEvents(new OldPickaxe(), $this);
        $this->getScheduler()->scheduleRepeatingTask(new ItemsScheduler, 20);
    }
}
