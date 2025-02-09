<?php

declare(strict_types = 0);

namespace lazyperson0710\WorldManagement\EventListener;

use lazyperson0710\WorldManagement\database\WorldCategory;
use lazyperson710\core\Main;
use lazyperson710\core\packet\SendNoSoundMessage\SendNoSoundTip;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\event\Listener;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;
use function in_array;

class PlayerDamageEvent implements Listener {

    private static array $damageFlags;

    public function __construct() {
        self::$damageFlags = [];
    }

    /**
     * @priority LOW
     */
    public function onDamage(EntityDamageEvent $event) : void {
        $entity = $event->getEntity();
        if (!$entity instanceof Player) {
            return;
        }
        switch ($event->getCause()) {
            case EntityDamageEvent::CAUSE_FALL:
                if (in_array($entity->getWorld()->getFolderName(), WorldCategory::PublicWorld, true)) {
                    $event->cancel();
                    break;
                }
                if (in_array($entity->getWorld()->getFolderName(), WorldCategory::PublicEventWorld, true)) {
                    $event->cancel();
                    break;
                }
                if (in_array($entity->getWorld()->getFolderName(), WorldCategory::PVP, true)) {
                    $event->cancel();
                    break;
                }
                if (isset(self::$damageFlags[$entity->getName()])) {
                    SendNoSoundTip::Send($entity, 'ワープ直前のためダメージが無効化されました', 'DamageProtect', true);
                    $event->cancel();
                    $this->unset($entity);
                }
                break;
            case EntityDamageEvent::CAUSE_SUFFOCATION:
                $event->cancel();
                break;
            case EntityDamageEvent::CAUSE_ENTITY_ATTACK:
            case EntityDamageEvent::CAUSE_PROJECTILE:
                if (!in_array($entity->getWorld()->getFolderName(), WorldCategory::PVP, true)) {
                    $event->cancel();
                }
                break;
        }
    }

    public function unset(Player $player) : void {
        unset(self::$damageFlags[$player->getName()]);
    }

    public function worldTeleport(EntityTeleportEvent $event) : void {
        $player = $event->getEntity();
        if ($player instanceof Player) {
            self::$damageFlags[$player->getName()] = true;
            if (in_array($event->getTo()->getWorld()->getFolderName(), WorldCategory::MiningWorld, true)) {
                Main::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(
                    function () use ($player) : void {
                        $this->unset($player);
                    }
                ), 20 * 25);
            } else {
                Main::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(
                    function () use ($player) : void {
                        $this->unset($player);
                    }
                ), 20 * 8);
            }
        }
    }
}
