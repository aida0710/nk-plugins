<?php

declare(strict_types = 0);

namespace lazyperson710\core\listener;

use lazyperson710\core\packet\SendMessage\SendActionBarMessage;
use lazyperson710\core\packet\SendNoSoundMessage\SendNoSoundTip;
use lazyperson710\core\task\IntervalTask;
use pocketmine\block\BaseSign;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\Server;
use function array_keys;
use function array_values;
use function floor;
use function str_replace;

class CmdSigns implements Listener {

    /**
     * @priority LOWEST
     */
    public function onTap(PlayerInteractEvent $event) : void {
        $block = $event->getBlock();
        if ($block instanceof BaseSign) {
            if ($block->getText()->getLine(0) == '##cmd') {
                if (!$event->getPlayer()->isSneaking()) {
                    if (IntervalTask::check($event->getPlayer(), 'CmdSigns')) {
                        SendNoSoundTip::Send($event->getPlayer(), 'コマンド看板は1秒以内に再度使用することは出来ません', 'CmdSigns', true);
                        return;
                    } else {
                        IntervalTask::onRun($event->getPlayer(), 'CmdSigns', 20);
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
                    SendActionBarMessage::Send($event->getPlayer(), 'スニーク中はコマンド看板は実行されず破壊が可能です', 'CmdSigns', true);
                }
            }
        }
    }
}
