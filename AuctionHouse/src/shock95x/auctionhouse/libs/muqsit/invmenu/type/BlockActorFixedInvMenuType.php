<?php

declare(strict_types = 1);
namespace shock95x\auctionhouse\libs\muqsit\invmenu\type;

use pocketmine\block\Block;
use pocketmine\inventory\Inventory;
use pocketmine\player\Player;
use pocketmine\world\World;
use shock95x\auctionhouse\libs\muqsit\invmenu\inventory\InvMenuInventory;
use shock95x\auctionhouse\libs\muqsit\invmenu\InvMenu;
use shock95x\auctionhouse\libs\muqsit\invmenu\type\graphic\BlockActorInvMenuGraphic;
use shock95x\auctionhouse\libs\muqsit\invmenu\type\graphic\InvMenuGraphic;
use shock95x\auctionhouse\libs\muqsit\invmenu\type\graphic\network\InvMenuGraphicNetworkTranslator;
use shock95x\auctionhouse\libs\muqsit\invmenu\type\util\InvMenuTypeHelper;

final class BlockActorFixedInvMenuType implements FixedInvMenuType {

    public function __construct(
        private Block                            $block,
        private int                              $size,
        private string                           $tile_id,
        private ?InvMenuGraphicNetworkTranslator $network_translator = null,
        private int                              $animation_duration = 0
    ) {
    }

    public function getSize(): int {
        return $this->size;
    }

    public function createGraphic(InvMenu $menu, Player $player): ?InvMenuGraphic {
        $origin = $player->getPosition()->addVector(InvMenuTypeHelper::getBehindPositionOffset($player))->floor();
        if ($origin->y < World::Y_MIN || $origin->y >= World::Y_MAX) {
            return null;
        }
        return new BlockActorInvMenuGraphic($this->block, $origin, BlockActorInvMenuGraphic::createTile($this->tile_id, $menu->getName()), $this->network_translator, $this->animation_duration);
    }

    public function createInventory(): Inventory {
        return new InvMenuInventory($this->size);
    }
}