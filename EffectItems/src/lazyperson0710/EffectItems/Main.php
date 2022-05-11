<?php

namespace lazyperson0710\EffectItems;

use lazyperson0710\EffectItems\tools\all\ExplosionTools;
use lazyperson0710\EffectItems\tools\all\OldTools;
use lazyperson0710\EffectItems\tools\all\TreasureDiscoveryTools;
use lazyperson0710\EffectItems\tools\others\AirBlock;
use lazyperson0710\EffectItems\tools\pickaxe\AlchemyPickaxe;
use lazyperson0710\EffectItems\tools\pickaxe\BlastFurnacePickaxe;
use lazyperson0710\EffectItems\tools\pickaxe\CursedPickaxe;
use lazyperson0710\EffectItems\tools\pickaxe\GlowstoneBreaker;
use lazyperson0710\EffectItems\tools\pickaxe\ObsidianBreaker;
use lazyperson0710\EffectItems\tools\pickaxe\OrePickaxe;
use lazyperson0710\EffectItems\tools\shovel\PoisonShovel;
use lazyperson0710\EffectItems\tools\shovel\WitherShovel;
use lazyperson0710\EffectItems\tools\task\RepetitionTask;
use lazyperson0710\EffectItems\tools\TestListener;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase {

    public function onEnable(): void {
        $manager = $this->getServer()->getPluginManager();
        $manager->registerEvents(new DamageEventListener(), $this);
        ##TestEventListener
        $manager->registerEvents(new TestListener(), $this);
        ##task
        $this->getScheduler()->scheduleRepeatingTask(new RepetitionTask(), 15);
        ##allTools
        $manager->registerEvents(new OldTools(), $this);
        $manager->registerEvents(new TreasureDiscoveryTools(), $this);
        $manager->registerEvents(new ExplosionTools(), $this);
        ##others
        $manager->registerEvents(new AirBlock(), $this);
        ##pickaxe
        $manager->registerEvents(new AlchemyPickaxe(), $this);
        $manager->registerEvents(new BlastFurnacePickaxe(), $this);
        $manager->registerEvents(new CursedPickaxe(), $this);
        $manager->registerEvents(new GlowstoneBreaker(), $this);
        $manager->registerEvents(new ObsidianBreaker(), $this);
        $manager->registerEvents(new OrePickaxe(), $this);
        ##shovel
        $manager->registerEvents(new PoisonShovel(), $this);
        $manager->registerEvents(new WitherShovel(), $this);
    }
}
