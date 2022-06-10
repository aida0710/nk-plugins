<?php

declare(strict_types=1);
namespace shock95x\auctionhouse\libs\muqsit\invmenu\type\graphic\network;

use pocketmine\network\mcpe\protocol\ContainerOpenPacket;
use shock95x\auctionhouse\libs\muqsit\invmenu\session\InvMenuInfo;
use shock95x\auctionhouse\libs\muqsit\invmenu\session\PlayerSession;

interface InvMenuGraphicNetworkTranslator {

    public function translate(PlayerSession $session, InvMenuInfo $current, ContainerOpenPacket $packet): void;
}