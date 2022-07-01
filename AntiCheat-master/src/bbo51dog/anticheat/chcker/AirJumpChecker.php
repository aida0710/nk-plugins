<?php

namespace bbo51dog\anticheat\chcker;

use bbo51dog\anticheat\Logger;
use bbo51dog\anticheat\Main;
use bbo51dog\anticheat\model\PlayerData;
use pocketmine\network\mcpe\protocol\Packet;
use pocketmine\network\mcpe\protocol\PlayerActionPacket;
use pocketmine\network\mcpe\protocol\types\PlayerAction;
use pocketmine\Server;

class AirJumpChecker extends Checker {

    public function __construct(PlayerData $playerData) {
        parent::__construct($playerData, "AntiAirJump", PHP_INT_MAX, 8);
    }

    public function handlePacket(Packet $packet): void {
        if ($packet instanceof PlayerActionPacket) {
            if ($packet->action === PlayerAction::JUMP) {
                if (!$this->getPlayerData()->getPlayer()->isOnGround()) {
                    $this->increaseViolation();
                }
            }
        }
    }

    public function checkViolation(): void {
        if ($this->getMaxViolation() > $this->getViolation()) {
            return;
        }
        Server::getInstance()->dispatchCommand($this->getPlayerData()->getPlayer(), Main::getSetting()->getAntiJumpCommand());
        Logger::getInstance()->warnPunishment($this);
        $this->reset();
        $this->getPlayerData()->getPlayer()->sendMessage("§bAntiCheat §7>> §c空中ジャンプを検知したためワープしました");
    }

    public function getCheatingMessage(): string {
        return "";
    }

    public function getPunishmentMessage(): string {
        return "{$this->getPlayerData()->getPlayer()->getName()} forced teleported to lobby due to AirJump";
    }
}