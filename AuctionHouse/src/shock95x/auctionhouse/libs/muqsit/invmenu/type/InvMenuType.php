<?php

declare(strict_types=1);
namespace shock95x\auctionhouse\libs\muqsit\invmenu\type;

use pocketmine\inventory\Inventory;
use pocketmine\player\Player;
use shock95x\auctionhouse\libs\muqsit\invmenu\InvMenu;
use shock95x\auctionhouse\libs\muqsit\invmenu\type\graphic\InvMenuGraphic;

interface InvMenuType {

    public function createGraphic(InvMenu $menu, Player $player): ?InvMenuGraphic;

    public function createInventory(): Inventory;
}