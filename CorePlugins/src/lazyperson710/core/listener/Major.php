<?php

declare(strict_types = 0);

namespace lazyperson710\core\listener;

use InvalidArgumentException;
use lazyperson710\core\packet\SendMessage\SendActionBarMessage;
use lazyperson710\core\packet\SendMessage\SendMessage;
use lazyperson710\core\packet\SendMessage\SendTip;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\math\Vector3;
use pocketmine\world\particle\RedstoneParticle;
use function round;

class Major implements Listener {

    public array $data = [];

    public function interactEvent(PlayerInteractEvent $event) {
        $this->setMajor($event);
    }

    public function setMajor(PlayerInteractEvent|BlockBreakEvent $event) {
        $player = $event->getPlayer();
        if ($player->getInventory()->getItemInHand()->getId() == 318) {
            $event->cancel();
            $name = $player->getName();
            if ($player->getInventory()->getItemInHand()->getName() === 'Major') {
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
                        SendActionBarMessage::Send($player, (round($distance, 2) + 1) . ' mです', 'Major', true);
                    } else {
                        $this->data[$name] = $block->getPosition();
                        SendMessage::Send($player, "始点のブロック座標を記録しました。 {$block->getPosition()->getX()}, {$block->getPosition()->getY()}, {$block->getPosition()->getZ()}", 'Major', true);
                        SendTip::Send($player, 'スニークしながらタップすることで座標を再設定出来ます', 'Major', true);
                    }
                } else {
                    unset($this->data[$name]);
                    SendTip::Send($player, '記録した座標のデータを削除しました', 'Major', true);
                }
            }
        }
    }

    public static function coordinateCalculation(Vector3 $start, Vector3 $end, float $percent) : Vector3 {
        if (0 > $percent || $percent > 1) {
            throw new InvalidArgumentException("percentage $percent should have a value of 0 to 1");
        }
        return $end->subtract($start->getFloorX(), $start->getFloorY(), $start->getFloorZ())->multiply($percent)->add($start->getFloorX(), $start->getFloorY(), $start->getFloorZ());
    }

    public function breakEvent(BlockBreakEvent $event) {
        $this->setMajor($event);
    }

}
