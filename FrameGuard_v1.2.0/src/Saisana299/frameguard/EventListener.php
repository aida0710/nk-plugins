<?php

namespace Saisana299\frameguard;

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
                        $player->sendTip("§bFrameLock §7>> §cこの額縁は" . $ownerf . "によって保護されています");
                        $event->cancel();
                    }
                } else {
                    $player->sendTip("§bFrameLock §7>> §cこの額縁は" . $ownerf . "によって保護されています");
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
                        $player->sendTip("§bFrameLock §7>> §cこの額縁は" . $ownerf . "によって保護されています");
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
                        $player->sendMessage("§bFrameLock §7>> §a額縁をタップしてください");
                        return;
                    }
                    $event->cancel();
                    $xyzl = $block->getPosition()->getX() . "," . $block->getPosition()->getY() . "," . $block->getPosition()->getZ() . "," . $block->getPosition()->getWorld()->getFolderName();
                    if (!$this->FrameGuard->config->exists($xyzl)) {
                        $this->FrameGuard->config->set($xyzl, $name);
                        $player->sendMessage("§bFrameLock §7>> §a額縁を保護しました");
                    } else {
                        $player->sendTip("§bFrameLock §7>> §c既に保護されています");
                    }
                    break;
                case 'delete':
                    if (!($block->getId() === 199)) {
                        $player->sendMessage("§bFrameLock §7>> §a額縁をタップしてください");
                        return;
                    }
                    $place = $block->getPosition()->getX() . "," . $block->getPosition()->getY() . "," . $block->getPosition()->getZ() . "," . $block->getPosition()->getWorld()->getFolderName();
                    if ($this->FrameGuard->config->exists($place)) {
                        $ownerf = $this->FrameGuard->config->get($block->getPosition()->getX() . "," . $block->getPosition()->getY() . "," . $block->getPosition()->getZ() . "," . $block->getPosition()->getWorld()->getFolderName());
                        if ($ownerf === $name) {
                            $this->FrameGuard->config->remove($place);
                            $player->sendMessage("§bFrameLock §7>> §a額縁の保護を解除しました");
                        } else {
                            if (Server::getInstance()->isOp($player->getName())) {
                                $this->FrameGuard->config->remove($place);
                                $player->sendMessage("§bFrameLock §7>> §a額縁の保護を解除しました");
                            }
                        }
                    } else {
                        $player->sendMessage("§bFrameLock §7>> §cこの額縁は保護されていません");
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
                    $player->sendTip("§bFrameLock §7>> §aこの額縁は" . $ownerf . "によって保護されています");
                    $event->cancel();
                } else {
                    $this->FrameGuard->config->remove($xyzl);
                    $player->sendMessage("§bFrameLock §7>> §a額縁の保護を解除しました");
                }
            } else {
                $this->FrameGuard->config->remove($xyzl);
                $player->sendMessage("§bFrameLock §7>> §a額縁の保護を解除しました");
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
                            $player->sendTip("§bFrameLock §7>> §c" . $ownerf . "によって保護されている額縁があるため壊せません");
                            $event->cancel();
                        } else {
                            $this->FrameGuard->config->remove($new_pos);
                            $player->sendMessage("§bFrameLock §7>> §cブロックが破壊されたため強制的にロックが解除されました");
                        }
                    } else {
                        $this->FrameGuard->config->remove($new_pos);
                        $player->sendMessage("§bFrameLock §7>> §cブロックが破壊されたため強制的にロックが解除されました");
                    }
                }
            }
        }
    }
}