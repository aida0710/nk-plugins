<?php

declare(strict_types=1);
namespace Deceitya\ChestLock\Event;

use Deceitya\ChestLock\Main;
use lazyperson710\core\packet\SendMessage\SendTip;
use pocketmine\block\Chest as BlockChest;
use pocketmine\block\inventory\DoubleChestInventory;
use pocketmine\block\tile\Chest;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\player\Player;
use pocketmine\Server;
use function count;
use function date;
use function explode;
use function in_array;
use function strtolower;

class EventListener implements Listener {

	private Main $plugin;

	public function __construct(Main $plugin) {
		$this->plugin = $plugin;
	}

	/**
	 * @priority HIGHEST
	 * @ignoreCancelled
	 */
	public function onPlayerInteract(PlayerInteractEvent $event) {
		$player = $event->getPlayer();
		$chest = $player->getWorld()->getTile($event->getBlock()->getPosition());
		if (!($chest instanceof Chest)) {
			return;
		}
		switch ($this->plugin->getStat($player)) {
			case 0:
				if (in_array($player->getWorld()->getFolderName(), $this->plugin->getEnabledWorlds(), true)) {
					SendTip::Send($player, "このワールドではチェストをロックすることは出来ません", "Lock", false);
					return;
				} else {
					$lock = $this->lockChest($chest, $player);
					SendTip::Send($player, "{$lock}個のチェストをロックしました", "Lock", true);
					$event->cancel();
					break;
				}
			case 1:
				$unlock = $this->unlockChest($chest, $player);
				SendTip::Send($player, "{$unlock}個のチェストのロックを解除しました", "Lock", true);
				$event->cancel();
				break;
			case 2:
				$info = $this->plugin->getData($chest->getPosition());
				if (count($info) > 0) {
					$date = date('Y年m月d日 H時i分s秒', $info['time']);
					$pos = explode(':', $info['pos']);
					SendTip::Send($player, "player | {$info['player']}\nlock | {$date}\n座標 | {$pos[0]}, {$pos[1]}, {$pos[2]}, {$pos[3]}", "", true);
				} else {
					SendTip::Send($player, "このチェストはロックされていません", "Lock", false);
				}
				$event->cancel();
				break;
			default:
				$data = $this->plugin->getData($chest->getPosition());
				if (count($data) > 0 && $data['player'] !== strtolower($player->getName())) {
					SendTip::Send($player, "このチェストは{$data['player']}がロックしています", "Lock", false);
					if (!Server::getInstance()->isOp($player->getName())) {
						$event->cancel();
					}
				}
				break;
		}
		$this->plugin->removeStat($player);
	}

	private function lockChest(Chest $chest, Player $player) : int {
		$c = 0;
		$lock = function (Chest $chest) use ($player, &$c) {
			if ($this->plugin->isChestLocked($chest->getPosition())) {
				return;
			}
			$this->plugin->lockChest($chest->getPosition(), $player);
			$c++;
		};
		$lock($chest);
		$pair = $chest->getPair();
		if ($pair instanceof Chest) {
			$lock($pair);
		}
		return $c;
	}

	private function unlockChest(Chest $chest, Player $player) : int {
		$c = 0;
		$name = strtolower($player->getName());
		$unlock = function (Chest $chest) use ($player, $name, &$c) {
			$data = $this->plugin->getData($chest->getPosition());
			if (count($data) <= 0) {
				return;
			}
			if (Server::getInstance()->isOp($name) || $data['player'] === $name) {
				$this->plugin->unlockChest($chest->getPosition());
				$c++;
			}
		};
		$unlock($chest);
		$pair = $chest->getPair();
		if ($pair instanceof Chest) {
			$unlock($pair);
		}
		return $c;
	}

	public function onBlockBreak(BlockBreakEvent $event) {
		$player = $event->getPlayer();
		$chest = $player->getPosition()->getWorld()->getTile($event->getBlock()->getPosition());
		if (!($chest instanceof Chest)) {
			return;
		}
		$data = $this->plugin->getData($chest->getPosition());
		if (count($data) <= 0) {
			return;
		}
		if ($data['player'] === strtolower($player->getName())) {
			$this->plugin->unlockChest($chest->getPosition());
			SendTip::Send($player, "1個のチェストのロックを解除しました", "Lock", true);
		} else {
			$event->cancel();
			SendTip::Send($player, "このチェストは{$data['player']}がロックしています", "Lock", false);
		}
	}

	public function onBlockPlace(BlockPlaceEvent $event) {
		$block = $event->getBlock();
		if (!($block instanceof BlockChest)) {
			return;
		}
		$player = $event->getPlayer();
		$name = strtolower($player->getName());
		$neighbor = function (Chest $chest) use ($player, $name, $block, &$event) {
			$data = $this->plugin->getData($chest->getPosition());
			if (count($data) <= 0) {
				return;
			}
			if ($data['player'] === $name) {
				$this->plugin->lockChest($block->getPosition(), $player);
				SendTip::Send($player, "1個のチェストをロックしました", "Lock", true);
			} else {
				$event->cancel();
				SendTip::Send($player, "隣のチェストは{$data['player']}がロックしています", "Lock", true);
			}
		};
		$world = $block->getPosition()->getWorld();
		foreach ($block->getHorizontalSides() as $sideBlock) {
			$chest = $world->getTile($sideBlock->getPosition());
			if (!($chest instanceof Chest)) {
				continue;
			}
			if ($chest->getInventory() instanceof DoubleChestInventory) {
				continue;
			}
			$neighbor($chest);
		}
	}

	public function onPlayerQuit(PlayerQuitEvent $event) {
		$this->plugin->removeStat($event->getPlayer());
	}
}
