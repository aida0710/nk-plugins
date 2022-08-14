<?php

namespace lazyperson710\core\listener;

use lazyperson0710\PlayerSetting\object\PlayerSettingPool;
use lazyperson0710\PlayerSetting\object\settings\DestructionSoundSetting;
use lazyperson710\core\packet\SoundPacket;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;

class BreakSoundPacket implements Listener {

    /**
     * @param BlockBreakEvent $event
     * @return void
     * @priority LOWEST
     */
    public function onBreak(BlockBreakEvent $event): void {
        if (PlayerSettingPool::getInstance()->getSettingNonNull($event->getPlayer())->getSetting(DestructionSoundSetting::getName())?->getValue() === true) {
            $volume = mt_rand(1, 2) / 10;
            $pitch = mt_rand(5, 10) / 10;
            SoundPacket::init($event->getPlayer(), "random.orb", $volume, $pitch);
        }
    }
}