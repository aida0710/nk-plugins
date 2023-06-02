<?php

declare(strict_types = 0);

namespace czechpmdevs\multiworld\libs\CortexPE\Commando\libs\muqsit\simplepackethandler\monitor;

use Closure;
use pocketmine\network\mcpe\NetworkSession;

interface IPacketMonitor {

    /**
     * @template TServerboundPacket of ServerboundPacket
     * @param Closure(TServerboundPacket, NetworkSession) : void $handler
     */
    public function monitorIncoming(Closure $handler) : IPacketMonitor;

    /**
     * @template TClientboundPacket of ClientboundPacket
     * @param Closure(TClientboundPacket, NetworkSession) : void $handler
     */
    public function monitorOutgoing(Closure $handler) : IPacketMonitor;

    /**
     * @template TServerboundPacket of ServerboundPacket
     * @param Closure(TServerboundPacket, NetworkSession) : void $handler
     */
    public function unregisterIncomingMonitor(Closure $handler) : IPacketMonitor;

    /**
     * @template TClientboundPacket of ClientboundPacket
     * @param Closure(TClientboundPacket, NetworkSession) : void $handler
     */
    public function unregisterOutgoingMonitor(Closure $handler) : IPacketMonitor;
}
