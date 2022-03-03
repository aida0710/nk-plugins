<?php

namespace lazyperson710\core\listener;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;

class Major implements Listener {

    public $data = [];

    public function ontap(PlayerInteractEvent $event) {
        $player = $event->getPlayer();
        if ($player->getInventory()->getItemInHand()->getId() == 318) {
            $name = $player->getName();
            if ($player->getInventory()->getItemInHand()->getName() === "Major") {
                if (!$player->isSneaking()) {
                    $block = $event->getblock();
                    if (isset($this->data[$name])) {
                        $distance = $this->data[$name]->distance($block->getPosition());
                        $player->sendActionBarMessage("§bMajor §7>> §a" . (round($distance, 2) + 1) . " mです");
                    } else {
                        $this->data[$name] = $block->getPosition();
                        $player->sendMessage("§bMajor §7>> §a始点のブロック座標を記録しました。 {$block->getPosition()->getX()}, {$block->getPosition()->getY()}, {$block->getPosition()->getZ()} \n§bMajor §7>> §aスニークしながらタップすることで座標を再設定出来ます");
                    }
                } else {
                    unset($this->data[$name]);
                    $player->sendMessage("§bMajor §7>> §a記録した座標のデータを削除しました");
                }
            }
        }
    }
}