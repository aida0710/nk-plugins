<?php

namespace bbo51dog\mjolnir\model;

use pocketmine\player\Player;

class Account {

    private string $name;

    private string $ip;

    private int $cid;

    private string $xuid;

    /**
     * @param string $name
     * @param string $ip
     * @param int $cid
     * @param string $xuid
     */
    public function __construct(string $name, string $ip, int $cid, string $xuid) {
        $this->name = strtolower($name);
        $this->ip = $ip;
        $this->cid = $cid;
        $this->xuid = $xuid;
    }

    public static function createFromPlayer(Player $player): self {
        return new Account($player->getName(), $player->getNetworkSession()->getIp(), $player->getPlayerInfo()->getExtraData()["ClientRandomId"], $player->getXuid());
    }

    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getIp(): string {
        return $this->ip;
    }

    /**
     * @return string
     */
    public function getCid(): string {
        return $this->cid;
    }

    /**
     * @return string
     */
    public function getXuid(): string {
        return $this->xuid;
    }

}