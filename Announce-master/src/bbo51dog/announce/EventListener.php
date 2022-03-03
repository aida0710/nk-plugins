<?php

namespace bbo51dog\announce;

use bbo51dog\announce\form\AnnounceForm;
use bbo51dog\announce\service\AnnounceService;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\server\CommandEvent;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class EventListener implements Listener {

    public function onJoin(PlayerJoinEvent $event) {
        $player = $event->getPlayer();
        $name = $player->getName();
        if (!AnnounceService::existsUser($name)) {
            AnnounceService::createUser($name);
        }
        if (AnnounceService::canRead($name) && !AnnounceService::hasAlreadyRead($name)) {
            $player->sendForm(new AnnounceForm(AnnounceService::getAnnounceIdByName($name)));
            AnnounceService::setAlreadyRead($name, true);
        }
    }

    public function onCommand(CommandEvent $event) {
        $str = explode(" ", $event->getCommand(), 2);
        if ($str[0] === "pass") {
            return;
        }
        $sender = $event->getSender();
        if (!$sender instanceof Player) {
            return;
        }
        if (!AnnounceService::isConfirmed($sender->getName())) {
            $event->cancel();
            $sender->sendPopup(MessageFormat::PREFIX_PASS . TextFormat::GREEN . "コマンドを使うには/passで機能をアクティブにする必要があります");
        }
    }
}