<?php

namespace Deceitya\SpecificationForm;

use Deceitya\SpecificationForm\Form\StartForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase {

    /* Memo
     * コマンドを分割し実行時にコマンドファイルの方から定義してもいいかも
     * インスタンス化してもいいかも
     * */
    private static array $function = [];
    private static array $specification = [];

    public static function getContents($file): array {
        return match ($file) {
            "function" => self::$function,
            "specification" => self::$specification,
            default => $none = [],
        };

    }

    public function onEnable(): void {
        $this->saveResource('function.json');
        $this->saveResource('specification.json');
        self::$specification = (new Config($this->getDataFolder() . 'function.json', Config::JSON))->getAll();
        self::$specification = (new Config($this->getDataFolder() . 'specification.json', Config::JSON))->getAll();
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        if ($sender instanceof Player) {
            $sender->sendForm(new StartForm());
        }
        return true;
    }
}
