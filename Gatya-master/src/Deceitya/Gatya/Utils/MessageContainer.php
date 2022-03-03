<?php

namespace Deceitya\Gatya\Utils;

use Deceitya\Gatya\Main;
use pocketmine\utils\TextFormat;

class MessageContainer {

    /** @var array[string=>string] */
    private static array $messages = [];

    private function __construct() {
    }

    public static function load(Main $plugin) {
        $stream = $plugin->getResource('message.json');
        self::$messages = json_decode(stream_get_contents($stream), true);
        fclose($stream);
    }

    public static function get(string $key, string ...$params): string {
        $keys = explode('.', $key);
        $msg = self::$messages;
        foreach ($keys as $k) {
            if (isset($msg[$k])) {
                $msg = $msg[$k];
            } else {
                $msg = $key;
                break;
            }
        }
        $i = 0;
        $search = [];
        $replace = [];
        foreach ($params as $param) {
            $search[] = '%' . ++$i;
            $replace[] = $param;
        }
        return TextFormat::colorize(str_replace($search, $replace, $msg));
    }
}
