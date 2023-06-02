<?php

declare(strict_types = 1);

namespace ymggacha\src\yomogi_server\ymggacha\gacha\text;

use pocketmine\utils\TextFormat;
use rarkhopper\gacha\GachaMessages;

class EffectableGachaMessage extends GachaMessages {

    public string $cantAddItem = TextFormat::RED . 'インベントリに空きが必要です';
    public string $alreadyRolling = TextFormat::RED . '他のガチャと同時に回すことはできません';
    public string $glitchTicket = TextFormat::RED . 'ガチャの演出中にチケットを破棄しないでください';
    public string $invalidWorld = TextFormat::RED . 'ガチャは生活ワールドや人工資源などでのみ回すことができます';
}
