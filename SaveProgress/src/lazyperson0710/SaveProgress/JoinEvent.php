<?php

namespace lazyperson0710\SaveProgress;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

class JoinEvent implements Listener {

    /**
     * @param PlayerJoinEvent $event
     * @return void
     */
    public function onJoin(PlayerJoinEvent $event): void {
        $player = $event->getPlayer();
        //初ログイン時(鯖が起動してから初めてのログイン)の処理
        if (!ProgressDataAPI::getInstance()->dataExists($player)) {
            ProgressDataAPI::getInstance()->createData($player);
        }
    }

}