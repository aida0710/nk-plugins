<?php

namespace bbo51dog\mjolnir;

use pocketmine\utils\SingletonTrait;

class Setting {

    use SingletonTrait;

    private string $kickMessage;

    private string $defaultBanReason;

    public function setData(array $datas): void {
        $this->kickMessage = $datas["messages"]["kick-message"];
        $this->defaultBanReason = $datas["messages"]["default-ban-reason"];
    }

    /**
     * @return string
     */
    public function getKickMessage(): string {
        return $this->kickMessage;
    }

    /**
     * @return string
     */
    public function getDefaultBanReason(): string {
        return $this->defaultBanReason;
    }
}