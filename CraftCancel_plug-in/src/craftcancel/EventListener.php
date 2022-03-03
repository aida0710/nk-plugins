<?php

namespace craftcancel;

use pocketmine\event\inventory\CraftItemEvent;
use pocketmine\event\Listener;

class EventListener implements Listener {

    public function __construct(array $ids) {
        $this->ids = $ids;
    }

    public function onCraft(CraftItemEvent $event) {
        foreach ($event->getOutputs() as $item) {
            if (!in_array($item->getId(), $this->ids)) {
                continue;
            }
            $event->cancel();
            $event->getPlayer()->sendMessage('§bCraftCancel §7>> §cこのアイテムはクラフト出来ません');
            return;
        }
    }
}
