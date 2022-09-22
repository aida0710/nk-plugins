<?php

namespace lazyperson0710\EffectItems\items;

use lazyperson710\core\packet\SendForm;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemUseEvent;

class PlayerTeleportTicket {

    public static function init(PlayerItemUseEvent|PlayerInteractEvent $event): void {
        $event->cancel();
        //todo teleport to player
        // 実装予定なし
        SendForm::Send($player,);
    }
}
