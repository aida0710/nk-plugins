<?php

namespace Deceitya\SBI;

use pocketmine\network\mcpe\protocol\RemoveObjectivePacket;
use pocketmine\network\mcpe\protocol\SetDisplayObjectivePacket;
use pocketmine\network\mcpe\protocol\SetScorePacket;
use pocketmine\network\mcpe\protocol\types\ScorePacketEntry;
use pocketmine\player\Player;
use pocketmine\scheduler\Task;

class DisplayTask extends Task {

    private Player $player;

    public function __construct(Player $player) {
        $this->player = $player;
    }

    public function onRun(): void {
        if (!$this->player->isOnline()) {
            $this->getHandler()->cancel();
            return;
        }
        $mode = Database::getInstance()->getMode($this->player);
        $this->player->getNetworkSession()->sendDataPacket(RemoveObjectivePacket::create("sidebar"));
        $lines = $mode->getLines($this->player);
        if ($lines === null) {
            return;
        }
        $this->player->getNetworkSession()->sendDataPacket(SetDisplayObjectivePacket::create("sidebar", "sidebar", $this->player->getName(), "dummy", 0));
        $entries = [];
        $score = 0;
        foreach ($lines as $line) {
            $entries[] = $this->createEntry($line, $score);
            $score++;
        }
        $this->player->getNetworkSession()->sendDataPacket(SetScorePacket::create(SetScorePacket::TYPE_CHANGE, $entries));
    }

    private function createEntry(string $customName, int $score): ScorePacketEntry {
        $entry = new ScorePacketEntry();
        $entry->objectiveName = "sidebar";
        $entry->type = ScorePacketEntry::TYPE_FAKE_PLAYER;
        $entry->customName = $customName;
        $entry->score = $score;
        $entry->scoreboardId = $score;
        return $entry;
    }
}