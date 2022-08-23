<?php

namespace lazyperson0710\EffectItems;

use lazyperson0710\EffectItems\event\PlayerBlockBreakEvent;
use lazyperson0710\EffectItems\event\PlayerBlockPlaceEvent;
use lazyperson0710\EffectItems\event\PlayerDamageEvent;
use lazyperson0710\EffectItems\event\PlayerItemEvent;
use lazyperson0710\EffectItems\task\RepetitionTask;
use lazyperson0710\Gacha\command\ItemNameChangeCommand;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase {

    public function onEnable(): void {
        $manager = $this->getServer()->getPluginManager();
        $manager->registerEvents(new PlayerBlockBreakEvent(), $this);
        $manager->registerEvents(new PlayerBlockPlaceEvent(), $this);
        $manager->registerEvents(new PlayerDamageEvent(), $this);
        $manager->registerEvents(new PlayerItemEvent(), $this);
        $this->getScheduler()->scheduleRepeatingTask(new RepetitionTask(), 10);
        $this->getServer()->getCommandMap()->registerAll("effectItems", [
            new ItemNameChangeCommand(),
        ]);
    }
}
