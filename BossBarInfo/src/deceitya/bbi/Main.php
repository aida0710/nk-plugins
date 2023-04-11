<?php

declare(strict_types = 0);

namespace deceitya\bbi;

use Deceitya\MiningLevel\MiningLevelAPI;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\plugin\PluginBase;
use xenialdan\apibossbar\BossBar;
use function round;

class Main extends PluginBase implements Listener {

	public function onEnable() : void {
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	public function onPlayerJoin(PlayerJoinEvent $event) {
		$player = $event->getPlayer();
		$api = MiningLevelAPI::getInstance();
		$level = $api->getLevel($player);
		$exp = $api->getExp($player);
		$upexp = $api->getLevelUpExp($player);
		if ($exp != 0) {
			$keiken = $exp / $upexp;
		} else {
			$keiken = 0;
		}
		$progress = $keiken * 100;
		$progress2 = round($progress, 0);
		$bar = (new BossBar())->setTitle("§a現在のレベル §f:§b {$level} §f|§a 進行度 §f:§b {$progress2}％")->setSubTitle("§a現在の経験値 §f:§b {$exp} §f|§a 次のレベルまで §f:§b {$upexp}")->setPercentage($keiken)->addPlayer($player);
		$task = new UpdateTask($bar, $player);
		$this->getScheduler()->scheduleRepeatingTask($task, 20);
	}
}
