<?php

declare(strict_types = 0);

namespace lazyperson0710\ticket\EventListener;

use lazyperson0710\miningtools\event\MiningToolsBreakEvent;
use lazyperson0710\ticket\TicketAPI;
use lazyperson710\core\packet\SendNoSoundMessage\SendNoSoundTip;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\player\Player;
use pocketmine\Server;
use function mt_rand;

class BreakEventListener implements Listener {

	private static array $ticket_temp = [];

	/**
	 * @priority MONITOR
	 */
	public function onBlockBreak(BlockBreakEvent $event) : void {
		if ($event->isCancelled()) return;
		var_dump("yes_normal");
		$this->setCache($event->getPlayer(), 'normal');
	}

	/**
	 * @priority MONITOR
	 */
	public function onMiningToolsBlockBreak(MiningToolsBreakEvent $event) : void {
		if ($event->isCancelled()) return;
		var_dump("yes_miningTools");
		$this->setCache($event->getPlayer(), 'miningTools');
	}

	private function setCache(Player $player, string $type) : void {
		if (!isset($this->ticket_temp[$player->getName()])) {
			self::$ticket_temp[$player->getName()]['normal']['count'] = 0;
			self::$ticket_temp[$player->getName()]['miningTools']['count'] = 0;
		}
		self::$ticket_temp[$player->getName()][$type]['count'] += 1;
		var_dump(self::$ticket_temp[$player->getName()][$type]['count']);
	}

	public function giveTicket() : void {
		$rand_normal = mt_rand(1, 800);
		$rand_miningTools = mt_rand(1, 1300);
		foreach (self::$ticket_temp as $player_name) {
			$count = 0;
			$normal = 0;
			$miningTools = 0;
			if (isset($player_name['normal'])) $normal = $player_name['normal']['count'];
			if (isset($player_name['miningTools'])) $miningTools = $player_name['miningTools']['count'];
			for ($k = 0; $k < $normal; $k++) {
				if ($rand_normal === 500) $count++;
			}
			for ($k = 0; $k < $miningTools; $k++) {
				if ($rand_miningTools === 500) $count++;
			}
			if ($count === 0) continue;
			if (!is_null($player = Server::getInstance()->getPlayerExact($player_name))) {
				SendNoSoundTip::Send($player, '3分間でチケットを' . $count . '枚獲得しました', 'Ticket', true);
			} elseif (is_null($player = Server::getInstance()->getOfflinePlayer($player_name))) throw new \RuntimeException('オフラインプレイヤーのデータが見つかりませんでした');
			TicketAPI::getInstance()->addTicket($player, $count);
		}
		var_dump('データの削除');
		self::$ticket_temp = [];
	}

}
