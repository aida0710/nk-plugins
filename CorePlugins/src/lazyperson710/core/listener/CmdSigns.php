<?php

namespace lazyperson710\core\listener;

use lazyperson710\core\Main;
use pocketmine\block\BaseSign;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;
use pocketmine\Server;

class CmdSigns implements Listener {

    private static array $CmdSignsInterval;

    public function __construct() {
        self::$CmdSignsInterval = [];
    }

    /**
     * @param PlayerInteractEvent $event
     * @priority LOWEST
     */
    public function onTap(PlayerInteractEvent $event) {
        $block = $event->getBlock();
        if ($block instanceof BaseSign) {
            if ($block->getText()->getLine(0) == "##cmd") {
                if (!$event->getPlayer()->isSneaking()) {
                    if (isset(self::$CmdSignsInterval[$event->getPlayer()->getName()])) {
                        $event->getPlayer()->sendTip("§bCmdSigns §7>> §cコマンド看板は1秒以内に再度使用することは出来ません");
                        return;
                    } else {
                        self::$CmdSignsInterval[$event->getPlayer()->getName()] = true;
                        Main::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(
                            function () use ($event): void {
                                $this->unset($event->getPlayer());
                            }
                        ), 20);
                    }
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

    public function unset(Player $player): void {
        unset(self::$CmdSignsInterval[$player->getName()]);
    }
}