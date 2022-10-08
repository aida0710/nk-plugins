<?php

namespace lazyperson710\core\listener;

use InvalidArgumentException;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\math\Vector3;
use pocketmine\world\particle\RedstoneParticle;

class Major implements Listener {

    public array $data = [];

    public function interactEvent(PlayerInteractEvent $event) {
        $this->setMajor($event);
    }

    public function breakEvent(BlockBreakEvent $event) {
        $this->setMajor($event);
    }

    public function setMajor(PlayerInteractEvent|BlockBreakEvent $event) {
        $player = $event->getPlayer();
        if ($player->getInventory()->getItemInHand()->getId() == 318) {
            $event->cancel();
            $name = $player->getName();
            if ($player->getInventory()->getItemInHand()->getName() === "Major") {
                $event->cancel();
                if (!$player->isSneaking()) {
                    $block = $event->getblock();
                    if (isset($this->data[$name])) {
                        $particle = new RedstoneParticle(15);
                        for ($i = 0; $i <= 10; $i += 0.5) {
                            $pos = $this->coordinateCalculation($this->data[$name]->add(0, 1, 0), $block->getPosition()->add(0, 1, 0), $i * 0.1);
                            $player->getWorld()->addParticle($pos, $particle);
                        }
                        $distance = $this->data[$name]->distance($block->getPosition());
                        $player->sendActionBarMessage("§bMajor §7>> §a" . (round($distance, 2) + 1) . " mです");
                    } else {
                        $this->data[$name] = $block->getPosition();
                        $player->sendMessage("§bMajor §7>> §a始点のブロック座標を記録しました。 {$block->getPosition()->getX()}, {$block->getPosition()->getY()}, {$block->getPosition()->getZ()}");
                        $player->sendTip("§bMajor §7>> §aスニークしながらタップすることで座標を再設定出来ます");
                    }
                } else {
                    unset($this->data[$name]);
                    $player->sendTip("§bMajor §7>> §a記録した座標のデータを削除しました");
                }
            }
        }
    }

    /**
     * @param Vector3 $start
     * @param Vector3 $end
     * @param float   $percent
     * @return Vector3
     */
    public static function coordinateCalculation(Vector3 $start, Vector3 $end, float $percent): Vector3 {
        if (0 > $percent or $percent > 1) {
            throw new InvalidArgumentException("percentage $percent should have a value of 0 to 1");
        }
        return $end->subtract($start->getFloorX(), $start->getFloorY(), $start->getFloorZ())->multiply($percent)->add($start->getFloorX(), $start->getFloorY(), $start->getFloorZ());
    }

}