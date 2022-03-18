<?php

namespace deceitya\miningTools\event;

use pocketmine\block\Block;
use pocketmine\event\block\BlockEvent;
use pocketmine\event\Cancellable;
use pocketmine\event\CancellableTrait;
use pocketmine\player\Player;

class CountBlockEvent extends BlockEvent implements Cancellable{
    use CancellableTrait;

    /** @var int[] intの配列 array(1, 1, 5, 6); */
    public array $blockIds;
    private Player $player;

    public function __construct(Player $player, Block $block) {
        parent::__construct($block);
        $this->player = $player;
    }

    /**
     * @return Player
     */
    public function getPlayer(): Player {
        return $this->player;
    }

}