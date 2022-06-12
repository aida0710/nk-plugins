<?php

namespace bbo51dog\anticheat\chcker;

use bbo51dog\anticheat\Logger;
use bbo51dog\anticheat\model\PlayerData;
use pocketmine\item\Tool;
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;
use pocketmine\network\mcpe\protocol\Packet;
use pocketmine\network\mcpe\protocol\types\inventory\UseItemTransactionData;

class BlockBreakChecker extends Checker {

    private float $previousTime = 0.0;

    public function __construct(PlayerData $playerData) {
        parent::__construct($playerData, "AntiFastBreak", 4, 8);
    }

    public function handlePacket(Packet $packet): void {
        if ($packet instanceof InventoryTransactionPacket) {
            $trData = $packet->trData;
            if ($trData instanceof UseItemTransactionData) {
                if ($trData->getActionType() === UseItemTransactionData::ACTION_BREAK_BLOCK) {
                    $currentTime = microtime(true);
                    $diff = $currentTime - $this->previousTime;
                    if ($this->getPlayerData()->getPlayer()->getInventory()->getItemInHand() instanceof Tool) {
                        if ($diff <= 0.003) {
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
            }
        }
    }

    public function checkViolation(): void {
        $ping = $this->getPlayerData()->getPlayer()->getNetworkSession()->getPing();
        if ($this->getMaxViolation() > $this->getViolation()) {
            if ($this->getViolation() >= $this->getWarningViolation()) {
                Logger::getInstance()->warnCheating($this);
            }
            return;
        }
        $this->getPlayerData()->getPlayer()->kick("チートの疑いでkickされました。通信状況を確認してください。");
        Logger::getInstance()->warnPunishment($this);
        $this->reset();
    }

    public function getCheatingMessage(): string {
        $player = $this->getPlayerData()->getPlayer();
        return "{$player->getName()} breaks blocks too fast. [{$this->getViolation()}/{$this->getMaxViolation()}] [Ping: {$player->getNetworkSession()->getPing()}ms]";
    }

    public function getPunishmentMessage(): string {
        $player = $this->getPlayerData()->getPlayer();
        return "{$player->getName()} was kicked by {$this->getName()}. [{$this->getViolation()}/{$this->getMaxViolation()}] [Ping: {$player->getNetworkSession()->getPing()}ms]";
    }
}