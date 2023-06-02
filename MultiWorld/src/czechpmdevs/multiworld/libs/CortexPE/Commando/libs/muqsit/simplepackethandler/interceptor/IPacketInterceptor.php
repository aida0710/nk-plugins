<?php

declare(strict_types = 0);

namespace czechpmdevs\multiworld\libs\CortexPE\Commando\libs\muqsit\simplepackethandler\interceptor;

use Closure;
use pocketmine\network\mcpe\NetworkSession;

interface IPacketInterceptor {

    /**
     * @template TServerboundPacket of ServerboundPacket
     * @param Closure(TServerboundPacket, NetworkSession) : bool $handler
     */
    public function interceptIncoming(Closure $handler) : IPacketInterceptor;

    /**
     * @template TClientboundPacket of ClientboundPacket
     * @param Closure(TClientboundPacket, NetworkSession) : bool $handler
     */
    public function interceptOutgoing(Closure $handler) : IPacketInterceptor;

    /**
     * @template TServerboundPacket of ServerboundPacket
     * @param Closure(TServerboundPacket, NetworkSession) : bool $handler
     */
    public function unregisterIncomingInterceptor(Closure $handler) : IPacketInterceptor;

    /**
     * @template TClientboundPacket of ClientboundPacket
     * @param Closure(TClientboundPacket, NetworkSession) : bool $handler
     */
    public function unregisterOutgoingInterceptor(Closure $handler) : IPacketInterceptor;
}
