<?php

declare(strict_types = 0);

namespace bbo51dog\anticheat\chcker;

use bbo51dog\anticheat\Logger;
use bbo51dog\anticheat\model\PlayerData;
use pocketmine\item\Tool;
use function microtime;

class BlockBreakChecker extends Checker {

    private float $previousTime = 0.0;

    public function __construct(PlayerData $playerData) {
        parent::__construct($playerData, 'AntiFastBreak', 4, 8);
    }

    public function blockBreak() : void {
        $currentTime = microtime(true);
        $diff = $currentTime - $this->previousTime;
        if ($this->getPlayerData()->getPlayer()->getInventory()->getItemInHand() instanceof Tool) {
            if ($diff <= 0.001) {
                $this->increaseViolation();
            } else {
                $this->decreaseViolation();
            }
        } elseif ($diff <= 0.06) {
            $this->increaseViolation();
        } else {
            $this->decreaseViolation();
        }
        $this->previousTime = $currentTime;
    }

    public function checkViolation() : void {
        $ping = $this->getPlayerData()->getPlayer()->getNetworkSession()->getPing();
        if ($this->getMaxViolation() > $this->getViolation()) {
            if ($this->getViolation() >= $this->getWarningViolation()) {
                Logger::getInstance()->warnCheating($this);
            }
            return;
        }
        $this->getPlayerData()->getPlayer()->kick("チートが検出された為、サーバーからkickされました\nPing : {$ping}");
        Logger::getInstance()->warnPunishment($this);
        $this->reset();
    }

    public function getCheatingMessage() : string {
        $player = $this->getPlayerData()->getPlayer();
        return "{$player->getName()} breaks blocks too fast. [{$this->getViolation()}/{$this->getMaxViolation()}] [Ping: {$player->getNetworkSession()->getPing()}ms]";
    }

    public function getPunishmentMessage() : string {
        $player = $this->getPlayerData()->getPlayer();
        return "{$player->getName()} was kicked by {$this->getName()}. [{$this->getViolation()}/{$this->getMaxViolation()}] [Ping: {$player->getNetworkSession()->getPing()}ms]";
    }
}
