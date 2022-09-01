<?php

declare(strict_types = 1);
namespace shock95x\auctionhouse\libs\muqsit\invmenu\type\graphic\network;

use pocketmine\network\mcpe\protocol\ContainerOpenPacket;
use shock95x\auctionhouse\libs\muqsit\invmenu\session\InvMenuInfo;
use shock95x\auctionhouse\libs\muqsit\invmenu\session\PlayerSession;

final class WindowTypeInvMenuGraphicNetworkTranslator implements InvMenuGraphicNetworkTranslator {

    public function __construct(
        private int $window_type,
    ) {
    }

    public function translate(PlayerSession $session, InvMenuInfo $current, ContainerOpenPacket $packet): void {
        $packet->windowType = $this->window_type;
    }
}