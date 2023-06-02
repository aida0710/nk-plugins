<?php

declare(strict_types = 1);

namespace ymggacha\src\yomogi_server\ymggacha\gacha;

trait OneLineDescriptionTrait {

    private string $oneLineDescription;

    public function getOneLineDescription() : string {
        return $this->oneLineDescription;
    }
}
