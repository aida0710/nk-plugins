<?php

declare(strict_types = 0);

namespace lazyperson0710\miningtools\event;

use pocketmine\block\Block;
use pocketmine\event\block\BlockEvent;
use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\player\Player;

class MiningToolsBreakEvent extends BlockEvent implements Cancellable {

    use CancellableTrait;

    private Player $player;

    public function __construct(Player $player, Block $block) {
        parent::__construct($block);
        $this->player = $player;
    }

    public function getPlayer() : Player {
        return $this->player;
    }

}
