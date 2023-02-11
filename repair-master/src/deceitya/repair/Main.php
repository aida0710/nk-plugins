<?php

declare(strict_types = 0);
namespace deceitya\repair;

use deceitya\repair\command\RepairCommand;
use deceitya\repair\form\RepairForm;
use Error;
use lazyperson710\core\packet\SendForm;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\Durable;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase implements Listener {

	public function onEnable() : void {
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getServer()->getCommandMap()->registerAll('sff', [
			new RepairCommand(),
		]);
	}

	public function onTap(PlayerInteractEvent $event) {
		$player = $event->getPlayer();
		$item = $player->getInventory()->getItemInHand();
		if (!($event->getBlock()->getId() === 145)) {
			return;
		}
		if (!$player->isSneaking()) {
			return;
		}
		if (RepairForm::checkItem($player) === false) {
			return;
		}
		$level = 5;
		foreach ($item->getEnchantments() as $enchant) {
			$level += 8 + $enchant->getLevel();
		}
		$mode = 'others';
		if (!$item instanceof Durable) throw new Error('道具以外のアイテムが指定されました');
		SendForm::Send($player, (new RepairForm($level, $item, $mode)));
	}
}
