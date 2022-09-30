<?php

namespace lazyperson0710\LoginBonus\event;

use lazyperson0710\LoginBonus\form\BonusForm;
use lazyperson0710\LoginBonus\Main;
use lazyperson710\core\packet\SendForm;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemUseEvent;

class LoginBonusItemTap implements Listener {

    public function onItemUse(PlayerItemUseEvent $event) {
        if ($event->isCancelled()) return;
        $this->onItemEvents($event);
    }

    public function onItemInteract(PlayerInteractEvent $event) {
        if ($event->isCancelled()) return;
        $this->onItemEvents($event);
    }

    public function onItemEvents(PlayerItemUseEvent|PlayerInteractEvent $event) {
        $player = $event->getPlayer();
        $inHand = $player->getInventory()->getItemInHand();
        if (Main::getInstance()->loginBonusItem === $inHand) {
            $event->cancel();
            SendForm::Send($player, new BonusForm($player));
        }
    }

}
