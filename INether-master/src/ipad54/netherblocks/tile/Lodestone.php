<?php

namespace ipad54\netherblocks\tile;

use pocketmine\block\tile\Spawnable;
use pocketmine\nbt\tag\CompoundTag;

class Lodestone extends Spawnable {

    public const TAG_TRACKING = "trackingHandle";

    protected int $lodestoneId = -1;

    public function getLodestoneId() : int {
        return $this->lodestoneId;
    }

    public function setLodestoneId(int $id) : self {
        $this->lodestoneId = $id;
        return $this;
    }

    public function readSaveData(CompoundTag $nbt) : void {
        $this->lodestoneId = $nbt->getInt(self::TAG_TRACKING);
    }

    protected function addAdditionalSpawnData(CompoundTag $nbt) : void {
        $nbt->setInt(self::TAG_TRACKING, $this->lodestoneId);
    }

    protected function writeSaveData(CompoundTag $nbt) : void {
        $nbt->setInt(self::TAG_TRACKING, $this->lodestoneId);
    }
}