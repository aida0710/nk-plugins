<?php

namespace lazyperson0710\EffectItems;

use lazyperson0710\EffectItems\tools\pickaxe\AlchemyPickaxe;
use lazyperson0710\EffectItems\tools\pickaxe\BlastFurnacePickaxe;
use lazyperson0710\EffectItems\tools\pickaxe\CursedPickaxe;
use lazyperson0710\EffectItems\tools\pickaxe\GlowstoneBreaker;
use lazyperson0710\EffectItems\tools\pickaxe\ObsidianBreaker;
use lazyperson0710\EffectItems\tools\pickaxe\OldPickaxe;
use lazyperson0710\EffectItems\tools\pickaxe\OrePickaxe;
use lazyperson0710\EffectItems\tools\pickaxe\task\PickaxeTask;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase {

    public function onEnable(): void {
        $manager = $this->getServer()->getPluginManager();
        $manager->registerEvents(new DamageEventListener(), $this);
        ##pickaxe
        $manager->registerEvents(new AlchemyPickaxe(), $this);
        $manager->registerEvents(new BlastFurnacePickaxe(), $this);
        $manager->registerEvents(new CursedPickaxe(), $this);
        $manager->registerEvents(new GlowstoneBreaker(), $this);
        $manager->registerEvents(new ObsidianBreaker(), $this);
        $manager->registerEvents(new OldPickaxe(), $this);
        $manager->registerEvents(new OrePickaxe(), $this);
        $this->getScheduler()->scheduleRepeatingTask(new PickaxeTask(), 20);
    }
}
