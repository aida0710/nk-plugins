<?php

namespace lazyperson710\core\listener;

use lazyperson710\core\Main;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\event\Listener;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;

class DamageListener implements Listener {

    private static array $damageFlags;

    public function __construct($damageFlag) {
        self::$damageFlags = [];
    }

    public function onDamage(EntityDamageEvent $event) {
        $entity = $event->getEntity();
        if (!$entity instanceof Player) {
            return;
        }
        switch ($event->getCause()) {
            case EntityDamageEvent::CAUSE_FALL:
                $worlds = [
                    "rule",
                    "lobby",
                    "event-1",
                    "pvp",
                    "athletic",
                ];
                if (in_array($entity->getWorld()->getFolderName(), $worlds)) {
                    $event->cancel();
                }
                if (isset(self::$damageFlags[$entity->getName()])) {
                    $entity->sendMessage("§bDamageProtect §7>> §cワープ直前のためダメージが無効化されました");
                    $event->cancel();
                }
                break;
            case EntityDamageEvent::CAUSE_SUFFOCATION:
                $event->cancel();
                break;
            case EntityDamageEvent::CAUSE_ENTITY_ATTACK:
            case EntityDamageEvent::CAUSE_PROJECTILE:
                if ($entity->getWorld()->getFolderName() !== "pvp") {
                    $event->cancel();
                }
                break;
        }
    }

    public function worldTeleport(EntityTeleportEvent $event) {
        $player = $event->getEntity();
        if ($player instanceof Player) {
            self::$damageFlags[$player->getName()] = true;
            Main::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(
                function () use ($player): void {
                    $this->unset($player);
                }
            ), 60);
        }
    }

    public function unset(Player $player): void {
        unset(self::$damageFlags[$player->getName()]);
    }
}