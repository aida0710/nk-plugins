<?php

namespace lazyperson710\core\listener;

use pocketmine\block\BaseSign;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\Server;

class Cmdsigns implements Listener {

    /**
     * @param PlayerInteractEvent $event
     * @priority LOWEST
     */
    public function onTap(PlayerInteractEvent $event) {
        $block = $event->getBlock();
        if ($block instanceof BaseSign) {
            if ($block->getText()->getLine(0) == "##cmd") {
                if (!$event->getPlayer()->isSneaking()) {
                    $player = $event->getPlayer();
                    $world = $player->getWorld()->getFolderName();
                    $floor_x = floor($player->getPosition()->getX());
                    $floor_y = floor($player->getPosition()->getY());
                    $floor_z = floor($player->getPosition()->getZ());
                    $table = [
                        '{name}' => $player->getName(),
                        '{x}' => $floor_x,
                        '{y}' => $floor_y,
                        '{z}' => $floor_z,
                        '{world}' => $world,
                    ];
                    $search = array_keys($table);
                    $replace = array_values($table);
                    $cmd = $block->getText()->getLine(2);
                    Server::getInstance()->dispatchCommand($event->getPlayer(), str_replace($search, $replace, $cmd));
                } else {
                    $event->getPlayer()->sendActionBarMessage("§bCmdSigns §7>> §aスニーク中はコマンド看板は実行されず破壊が可能です");
                }
            }
        }
    }
}