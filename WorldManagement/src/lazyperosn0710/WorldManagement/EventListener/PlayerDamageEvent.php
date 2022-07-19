<?php

namespace lazyperson710\core\listener;

use lazyperson0710\WorldManagement\database\WorldCategory;
use lazyperson0710\WorldManagement\database\WorldManagementAPI;
use lazyperson710\core\Main;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\event\Listener;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;

class DamageListener implements Listener {

    private static array $damageFlags;

    public function __construct() {
        self::$damageFlags = [];
    }

    /**
     * @param EntityDamageEvent $event
     * @return void
     * @priority LOW
     */
    public function onDamage(EntityDamageEvent $event): void {
        $entity = $event->getEntity();
        if (!$entity instanceof Player) {
            return;
        }
        switch ($event->getCause()) {
            case EntityDamageEvent::CAUSE_FALL:
                if (in_array($entity->getWorld()->getFolderName(), WorldCategory::PublicWorld)) {
                    $event->cancel();
                }
                if (in_array($entity->getWorld()->getFolderName(), WorldCategory::PublicEventWorld)) {
                    $event->cancel();
                }
                if (in_array($entity->getWorld()->getFolderName(), WorldCategory::PVP)) {
                    $event->cancel();
                }
                if (isset(self::$damageFlags[$entity->getName()])) {
                    $entity->sendTip("§bDamageProtect §7>> §cワープ直前のためダメージが無効化されました");
                    $event->cancel();
                    $this->unset($entity->getName());
                }
                break;
            case EntityDamageEvent::CAUSE_SUFFOCATION:
                $event->cancel();
                break;
            case EntityDamageEvent::CAUSE_ENTITY_ATTACK:
            case EntityDamageEvent::CAUSE_PROJECTILE:
            if (in_array($entity->getWorld()->getFolderName(), WorldCategory::PVP)) {
                $event->cancel();
            }
                break;
        }
    }

    public function worldTeleport(EntityTeleportEvent $event) {
        $player = $event->getEntity();
        if ($player instanceof Player) {
            self::$damageFlags[$player->getName()] = true;
            if (in_array($event->getTo()->getWorld()->getFolderName(), WorldCategory::MiningWorld)) {
                Main::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(
                    function () use ($player): void {
                        $this->unset($player);
                    }
                ), 20 * 25);
            } else {
                Main::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(
                    function () use ($player): void {
                        $this->unset($player);
                    }
                ), 20 * 8);
            }
        }
    }

    public function unset(Player $player): void {
        unset(self::$damageFlags[$player->getName()]);
    }
}