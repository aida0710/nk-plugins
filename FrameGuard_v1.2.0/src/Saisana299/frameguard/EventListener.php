<?php

declare(strict_types = 1);
namespace Saisana299\frameguard;

use lazyperson710\core\packet\SendMessage\SendMessage;
use lazyperson710\core\packet\SendMessage\SendTip;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\math\Facing;
use pocketmine\network\mcpe\protocol\ItemFrameDropItemPacket;
use pocketmine\Server;

class EventListener implements Listener {

	private FrameGuard $FrameGuard;

	public function __construct(FrameGuard $FrameGuard) {
		$this->FrameGuard = $FrameGuard;
	}

	public function onReceived(DataPacketReceiveEvent $event) {
		$packet = $event->getPacket();
		if ($packet instanceof ItemFrameDropItemPacket) {
			$player = $event->getOrigin()->getPlayer();
			$name = $player->getName();
			$pos = $packet->blockPosition;
			$xyzl = $pos->getX() . "," . $pos->getY() . "," . $pos->getZ() . "," . $player->getPosition()->getWorld()->getFolderName();
			if ($this->FrameGuard->config->exists($xyzl)) {
				$ownerf = $this->FrameGuard->config->get($xyzl);
				if (!Server::getInstance()->isOp($player->getName())) {
					if ($ownerf != $name) {
						SendTip::Send($player, "この額縁は" . $ownerf . "によって保護されています", "FrameLock", false);
						$event->cancel();
					}
				} else {
					SendTip::Send($player, "この額縁は" . $ownerf . "によって保護されています", "FrameLock", false);
				}
			}
		}
	}

	public function onTouch(PlayerInteractEvent $event) {
		$player = $event->getPlayer();
		$block = $event->getBlock();
		$name = $player->getName();
		if ($block->getId() === 199) {
			$xyzl = $block->getPosition()->getX() . "," . $block->getPosition()->getY() . "," . $block->getPosition()->getZ() . "," . $player->getPosition()->getWorld()->getFolderName();
			if ($this->FrameGuard->config->exists($xyzl)) {
				$ownerf = $this->FrameGuard->config->get($block->getPosition()->getX() . "," . $block->getPosition()->getY() . "," . $block->getPosition()->getZ() . "," . $player->getPosition()->getWorld()->getFolderName());
				if (!Server::getInstance()->isOp($player->getName())) {
					if ($ownerf != $name) {
						SendTip::Send($player, "この額縁は" . $ownerf . "によって保護されています", "FrameLock", false);
						$event->cancel();
						return;
					}
				}
			}
		}
		if (isset($this->FrameGuard->frame[$name])) {
			switch ($this->FrameGuard->frame[$name]["type"]) {
				case 'add':
					if (!($block->getId() === 199)) {
						SendMessage::Send($player, "額縁をタップしてください", "FrameLock", true);
						return;
					}
					$event->cancel();
					$xyzl = $block->getPosition()->getX() . "," . $block->getPosition()->getY() . "," . $block->getPosition()->getZ() . "," . $block->getPosition()->getWorld()->getFolderName();
					if (!$this->FrameGuard->config->exists($xyzl)) {
						$this->FrameGuard->config->set($xyzl, $name);
						SendMessage::Send($player, "額縁を保護しました", "FrameLock", true);
					} else {
						SendMessage::Send($player, "既に保護されています", "FrameLock", false);
					}
					break;
				case 'delete':
					if (!($block->getId() === 199)) {
						SendMessage::Send($player, "額縁をタップしてください", "FrameLock", true);
						return;
					}
					$place = $block->getPosition()->getX() . "," . $block->getPosition()->getY() . "," . $block->getPosition()->getZ() . "," . $block->getPosition()->getWorld()->getFolderName();
					if ($this->FrameGuard->config->exists($place)) {
						$ownerf = $this->FrameGuard->config->get($block->getPosition()->getX() . "," . $block->getPosition()->getY() . "," . $block->getPosition()->getZ() . "," . $block->getPosition()->getWorld()->getFolderName());
						if ($ownerf === $name) {
							$this->FrameGuard->config->remove($place);
							SendMessage::Send($player, "額縁の保護を解除しました", "FrameLock", true);
						} else {
							if (Server::getInstance()->isOp($player->getName())) {
								$this->FrameGuard->config->remove($place);
								SendMessage::Send($player, "額縁の保護を解除しました", "FrameLock", true);
							}
						}
					} else {
						SendTip::Send($player, "この額縁は保護されていません", "FrameLock", false);
					}
					break;
			}
		}
	}

	public function onBreakEvent(BlockBreakEvent $event) {
		$player = $event->getPlayer();
		$name = $player->getName();
		$block = $event->getBlock();
		$xyzl = $block->getPosition()->getX() . "," . $block->getPosition()->getY() . "," . $block->getPosition()->getZ() . "," . $block->getPosition()->getWorld()->getFolderName();
		if ($this->FrameGuard->config->exists($xyzl)) {
			$ownerf = $this->FrameGuard->config->get($block->getPosition()->getX() . "," . $block->getPosition()->getY() . "," . $block->getPosition()->getZ() . "," . $block->getPosition()->getWorld()->getFolderName());
			if (!Server::getInstance()->isOp($player->getName())) {
				if ($ownerf != $name) {
					SendTip::Send($player, "この額縁は" . $ownerf . "によって保護されています", "FrameLock", false);
					$event->cancel();
				} else {
					$this->FrameGuard->config->remove($xyzl);
					SendMessage::Send($player, "額縁の保護を解除しました", "FrameLock", true);
				}
			} else {
				$this->FrameGuard->config->remove($xyzl);
				SendMessage::Send($player, "額縁の保護を解除しました", "FrameLock", true);
			}
		} else {
			foreach ($block->getHorizontalSides() as $sideBlock) {
				if ($sideBlock->getId() !== 199) {
					return;
				}
				if ($sideBlock->getMeta() === 0) {
					$new_block = $sideBlock->getSide(Facing::WEST);
					if ($new_block->getPosition()->getX() . $new_block->getPosition()->getY() . $new_block->getPosition()->getZ() !== $block->getPosition()->getX() . $block->getPosition()->getY() . $block->getPosition()->getZ()) {
						return;
					}
				} elseif ($sideBlock->getMeta() === 1) {
					$new_block = $sideBlock->getSide(Facing::EAST);
					if ($new_block->getPosition()->getX() . $new_block->getPosition()->getY() . $new_block->getPosition()->getZ() !== $block->getPosition()->getX() . $block->getPosition()->getY() . $block->getPosition()->getZ()) {
						return;
					}
				} elseif ($sideBlock->getMeta() === 2) {
					$new_block = $sideBlock->getSide(Facing::NORTH);
					if ($new_block->getPosition()->getX() . $new_block->getPosition()->getY() . $new_block->getPosition()->getZ() !== $block->getPosition()->getX() . $block->getPosition()->getY() . $block->getPosition()->getZ()) {
						return;
					}
				} elseif ($sideBlock->getMeta() === 3) {
					$new_block = $sideBlock->getSide(Facing::SOUTH);
					if ($new_block->getPosition()->getX() . $new_block->getPosition()->getY() . $new_block->getPosition()->getZ() !== $block->getPosition()->getX() . $block->getPosition()->getY() . $block->getPosition()->getZ()) {
						return;
					}
				}
				$pos = $sideBlock->getPosition();
				$new_pos = $pos->getX() . "," . $pos->getY() . "," . $pos->getZ() . "," . $pos->getWorld()->getFolderName();
				if ($this->FrameGuard->config->exists($new_pos)) {
					$ownerf = $this->FrameGuard->config->get($new_pos);
					if (!Server::getInstance()->isOp($player->getName())) {
						if ($ownerf != $name) {
							SendTip::Send($player, $ownerf . "によって保護されている額縁があるため壊せません", "FrameLock", false);
							$event->cancel();
						} else {
							$this->FrameGuard->config->remove($new_pos);
							SendMessage::Send($player, "ブロックが破壊されたため強制的にロックが解除されました", "FrameLock", true);
						}
					} else {
						$this->FrameGuard->config->remove($new_pos);
						SendMessage::Send($player, "ブロックが破壊されたため強制的にロックが解除されました", "FrameLock", true);
					}
				}
			}
		}
	}
}
