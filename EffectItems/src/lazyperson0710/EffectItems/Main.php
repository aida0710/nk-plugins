<?php

declare(strict_types=1);
namespace lazyperson0710\EffectItems;

use lazyperson0710\EffectItems\command\ItemsCommand;
use lazyperson0710\EffectItems\event\PlayerBlockBreakEvent;
use lazyperson0710\EffectItems\event\PlayerBlockPlaceEvent;
use lazyperson0710\EffectItems\event\PlayerDamageEvent;
use lazyperson0710\EffectItems\event\PlayerItemEvent;
use lazyperson0710\EffectItems\task\RepetitionTask;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase {

	public static Main $instance;

	public function onEnable() : void {
		self::$instance = $this;
		$manager = $this->getServer()->getPluginManager();
		$manager->registerEvents(new PlayerBlockBreakEvent(), $this);
		$manager->registerEvents(new PlayerBlockPlaceEvent(), $this);
		$manager->registerEvents(new PlayerDamageEvent(), $this);
		$manager->registerEvents(new PlayerItemEvent(), $this);
		//ItemFactory::getInstance()->register(new Churu(new ItemIdentifier(VanillaItems::COOKED_MUTTON()->getId(), 0), "Churu"), true);
		$this->getScheduler()->scheduleRepeatingTask(new RepetitionTask(), 10);
		$this->getServer()->getCommandMap()->registerAll("effectItems", [
			new ItemsCommand(),
		]);
	}

	public static function getInstance() : Main {
		return self::$instance;
	}
}
