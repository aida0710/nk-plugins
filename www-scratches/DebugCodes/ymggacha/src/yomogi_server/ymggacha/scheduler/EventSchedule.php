<?php

declare(strict_types = 1);

namespace ymggacha\src\yomogi_server\ymggacha\scheduler;

use LogicException;
use pocketmine\scheduler\ClosureTask;
use ymggacha\src\yomogi_server\ymggacha\YmgGachaPlugin;

class EventSchedule {

	/** @var array<ScheduledEvent> */
	private array $events = [];

	public function register(ScheduledEvent $ev) : EventSchedule {
		$this->events[] = $ev;
		return $this;
	}

	/**
	 * @throws LogicException
	 */
	public function execute() : void {
		$scheduler = YmgGachaPlugin::getTaskScheduler() ?? throw new LogicException('can not a execute in before enabled YmgGachaPlugin');
		foreach ($this->events as $ev) {
			$scheduler->scheduleDelayedTask(new ClosureTask(fn () => $ev->getFunction()()), $ev->getDelay());
		}
	}
}
