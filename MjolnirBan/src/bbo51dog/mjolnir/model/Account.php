<?php

namespace bbo51dog\mjolnir\model;

use pocketmine\player\Player;

class Account {

    private string $name;

    private int $cid;

    private string $xuid;

    /**
     * @param string $name
     * @param int $cid
     * @param string $xuid
     */
    public function __construct(string $name, int $cid, string $xuid) {
        $this->name = strtolower($name);
        $this->cid = $cid;
        $this->xuid = $xuid;
    }

    public static function createFromPlayer(Player $player): self {
        return new Account($player->getName(), $player->getPlayerInfo()->getExtraData()["ClientRandomId"], $player->getXuid());
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
    public function getXuid(): string {
        return $this->xuid;
    }

    /**
     * @return string
     */
    public function getCid(): string {
        return $this->cid;
    }

}