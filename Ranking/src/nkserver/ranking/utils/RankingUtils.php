<?php

declare(strict_types=1);
namespace nkserver\ranking\utils;

use nkserver\ranking\object\PlayerDataArray;
use nkserver\ranking\object\PlayerDataPool;
use pocketmine\utils\TextFormat;

abstract class RankingUtils {

    final private function __construct() {
        /** NOOP */
    }

    public static function createRanking(
        string  $type,
        ?string $must_render = null,
        ?int    $max_render = null,
        ?string $level = null,
        ?int    $id = null
    ): string {
        $data_array = [];
        foreach (PlayerDataPool::getAll() as $name => $data) {
            $data_array[$name] = (int)match ($type) {
                PlayerDataArray::BLOCK_BREAKS => $data->getBlockBreaks($level, $id),
                PlayerDataArray::BLOCK_PLACES => $data->getBlockPlaces($level, $id),
                PlayerDataArray::CHAT => $data->getChat(),
                PlayerDataArray::DEATH => $data->getDeath(),
                default => -255
            };
        }
        return self::createTxt($data_array, strtolower($must_render), $max_render);
    }

    public static function createTxt(array $data, ?string $must_render_player = null, ?int $max_render = null): string {
        $i = 0;
        $ranking = '';
        $is_rendered = $must_render_player === null;
        array_multisort($data, SORT_NUMERIC, SORT_DESC);
        foreach ($data as $name => $score) {
            ++$i;
            $place_txt = '【' . $i . '】  ' . TextFormat::RESET;
            $header = match ($i) {
                1 => TextFormat::BOLD . TextFormat::YELLOW . $place_txt . TextFormat::BOLD,
                2 => TextFormat::BOLD . TextFormat::GRAY . $place_txt . TextFormat::BOLD,
                3 => TextFormat::BOLD . TextFormat::GOLD . $place_txt . TextFormat::BOLD,
                default => TextFormat::WHITE . $place_txt
            };
            if ($max_render !== null and $max_render < $i) {
                if ($is_rendered) break;
            } else {
                $ranking .= $header . $name . ' - ' . TextFormat::RED . number_format((int)$score) . PHP_EOL;
            }
            if ($name === $must_render_player) {
                $ranking = TextFormat::GRAY . 'YOU >> ' . $header . $name . ' - ' . TextFormat::RED . number_format((int)$score) . PHP_EOL . PHP_EOL . $ranking;
                $is_rendered = true;
            }
        }
        return $ranking;
    }
}