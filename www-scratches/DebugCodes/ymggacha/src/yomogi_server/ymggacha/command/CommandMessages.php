<?php

declare(strict_types = 1);

namespace ymggacha\src\yomogi_server\ymggacha\command;

use pocketmine\utils\TextFormat;

interface CommandMessages {

    public const EXECUTED_NON_PLAYER = TextFormat::RED . 'プレイヤー以外が実行することはできません';
    public const EXECUTED_NOT_OP = TextFormat::RED . 'OP以外のプレイヤーは実行できません';
    public const INVALID_ROLL_CNT = TextFormat::RED . 'ガチャを回す回数は10回以上や1回未満の場合無効になります';
    public const INVALID_FORMAT_ROLL_CNT = TextFormat::RED . 'ガチャを回す回数は半角整数で入力してください';
    public const INVALID_FORMAT_TICKET_CNT = TextFormat::RED . '付与するガチャチケットをの個数を半角整数で入力してください';
    public const ADDED_TICKET = TextFormat::WHITE . 'ガチャチケットを%amount個付与しました';
}
