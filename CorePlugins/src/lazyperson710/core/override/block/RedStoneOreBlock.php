<?php

declare(strict_types = 1);

namespace lazyperson710\core\override\block;

use pocketmine\block\RedstoneOre;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\player\Player;

class RedStoneOreBlock extends RedstoneOre {

    protected bool $lit = false;

    public function getLightLevel() : int {
        return 0;
    }

    public function isLit() : bool {
        return $this->lit;
    }

    /**
     * @return $this
     */
    public function setLit(bool $lit = true) : self {
        return $this;
    }

    public function onInteract(Item $item, int $face, Vector3 $clickVector, ?Player $player = null) : bool {
        return false;
    }

    public function onNearbyBlockChange() : void {
        #none
    }

    public function readStateFromData(int $id, int $stateMeta) : void {
        $this->lit = $id === $this->idInfoFlattened->getSecondId();
    }

    public function ticksRandomly() : bool {
        return false;
    }

}
