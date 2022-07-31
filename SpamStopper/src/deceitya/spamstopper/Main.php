<?php

namespace deceitya\spamstopper;

use Deceitya\MiningLevel\MiningLevelAPI;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;

class Main extends PluginBase implements Listener {

    private array $spam = [];

    private static array $interval;

    public function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        self::$interval = [];
    }

    public function onChat(PlayerChatEvent $event) {
        $name = $event->getPlayer()->getName();
        $msg = $event->getMessage();
        if (MiningLevelAPI::getInstance()->getLevel($event->getPlayer()) >= 30) {
            if (isset(self::$interval[$event->getPlayer()->getName()])) {
                $event->getPlayer()->sendMessage("§bSpamBlock §7>> §cチャットは1秒以内に再度送信することは出来ません");
                $event->cancel();
            } else {
                self::$interval[$event->getPlayer()->getName()] = true;
                $this->getScheduler()->scheduleDelayedTask(new ClosureTask(
                    function () use ($event): void {
                        $this->unset($event->getPlayer());
                    }
                ), 20);
            }
        } else {
            if (isset($this->spam[$name]) && $this->spam[$name] == $msg) {
                $event->cancel();
                $event->getPlayer()->sendMessage("§bSpamBlock §7>> §c30レベル未満のプレイヤーは連続して同じメッセージは送信できません");
                return;
            }
            if (isset(self::$interval[$event->getPlayer()->getName()])) {
                $event->getPlayer()->sendMessage("§bSpamBlock §7>> §c30レベル未満のプレイヤーはチャット後5秒以内は連続してメッセージを送信できません");
                $event->cancel();
            } else {
                self::$interval[$event->getPlayer()->getName()] = true;
                $this->getScheduler()->scheduleDelayedTask(new ClosureTask(
                    function () use ($event): void {
                        $this->unset($event->getPlayer());
                    }
                ), 20 * 5);
            }
        }
        $this->spam[$name] = $msg;
    }

    public function onJoin(PlayerJoinEvent $event) {
        $this->spam[$event->getPlayer()->getName()] = false;
    }

    public function unset(Player $player): void {
        unset(self::$interval[$player->getName()]);
    }
}
