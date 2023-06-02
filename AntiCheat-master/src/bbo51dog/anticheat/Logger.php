<?php

declare(strict_types = 0);

namespace bbo51dog\anticheat;

use bbo51dog\anticheat\chcker\Checker;
use bbo51dog\pmdiscord\connection\Webhook;
use bbo51dog\pmdiscord\element\Embed;
use bbo51dog\pmdiscord\element\Embeds;
use DateTime;
use DateTimeInterface;
use lazyperson710\core\packet\SendMessage\SendMessage;
use pocketmine\Server;
use pocketmine\utils\SingletonTrait;

class Logger {

    use SingletonTrait;

    public function warnCheating(Checker $checker) : void {
        $this->warnToAdmin($checker->getCheatingMessage());
        if (!Main::getSetting()->isEnableDiscordLog()) {
            return;
        }
        $player = $checker->getPlayerData()->getPlayer();
        $pos = $player->getPosition();
        $webhook = Webhook::create(Main::getSetting()->getWebhookUrl());
        $embed = (new Embed())
            ->setTitle('Warning')
            ->setColor(16776960)
            ->setAuthorName('AntiCheat')
            ->setDesc('チート使用の疑いがあります')
            ->setTime((new DateTime())->format(DateTimeInterface::ATOM))
            ->addField('Player', $player->getName(), true)
            ->addField('Detected by', $checker->getName(), true)
            ->addField('Violation point', "{$checker->getViolation()}/{$checker->getMaxViolation()}", true)
            ->addField('Position', "({$pos->getFloorX()}, {$pos->getFloorY()}, {$pos->getFloorZ()}, {$pos->getWorld()->getFolderName()})", true)
            ->addField('Ping', $player->getNetworkSession()->getPing() . 'ms', true);
        $embeds = new Embeds();
        $embeds->add($embed);
        $webhook->add($embeds);
        $webhook->send();
    }

    public function warnPunishment(Checker $checker) : void {
        $this->warnToAdmin($checker->getPunishmentMessage());
        if (!Main::getSetting()->isEnableDiscordLog()) {
            return;
        }
        $player = $checker->getPlayerData()->getPlayer();
        $pos = $player->getPosition();
        $webhook = Webhook::create(Main::getSetting()->getWebhookUrl());
        $embed = (new Embed())
            ->setTitle('Punishment')
            ->setColor(16711680)
            ->setAuthorName('AntiCheat')
            ->setDesc('チート使用を検知し、処罰しました')
            ->setTime((new DateTime())->format(DateTimeInterface::ATOM))
            ->addField('Player', $player->getName(), true)
            ->addField('Detected by', $checker->getName(), true)
            ->addField('Violation point', "{$checker->getViolation()}/{$checker->getMaxViolation()}", true)
            ->addField('Position', "({$pos->getFloorX()}, {$pos->getFloorY()}, {$pos->getFloorZ()}, {$pos->getWorld()->getFolderName()})", true)
            ->addField('Ping', $player->getNetworkSession()->getPing() . 'ms', true);
        $embeds = new Embeds();
        $embeds->add($embed);
        $webhook->add($embeds);
        $webhook->send();
    }

    private function warnToAdmin(string $message) : void {
        foreach (Server::getInstance()->getOnlinePlayers() as $player) {
            if (!Server::getInstance()->isOp($player->getName())) {
                continue;
            }
            SendMessage::Send($player, $message, 'Warning', false);
        }
        Server::getInstance()->getLogger()->warning($message);
    }
}
