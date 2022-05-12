<?php

namespace lazyperson710\sff;

use lazyperson710\sff\command\BonusCommand;
use lazyperson710\sff\command\EnchantCommand;
use lazyperson710\sff\command\FlyCommand;
use lazyperson710\sff\command\InvClearCommand;
use lazyperson710\sff\command\LockCommand;
use lazyperson710\sff\command\MiningToolsCommand;
use lazyperson710\sff\command\PlayerCommand;
use lazyperson710\sff\command\RecipeCommand;
use lazyperson710\sff\command\ShopCommand;
use lazyperson710\sff\command\TosCommand;
use lazyperson710\sff\command\WpCommand;
use lazyperson710\sff\listener\CmdListener;
use lazyperson710\sff\listener\CommandStorageListener;
use lazyperson710\sff\listener\ItemListener;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase {

    private const TOS_FILE = "tos.text";

    private static string $tos;

    public static function getTos(): string {
        return self::$tos;
    }

    public function onEnable(): void {
        $this->loadTos();
        $this->getServer()->getPluginManager()->registerEvents(new ItemListener(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new CmdListener(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new CommandStorageListener(), $this);
        $this->getServer()->getCommandMap()->registerAll("sff", [
            new TosCommand(),
            new WpCommand(),
            new LockCommand(),
            new EnchantCommand(),
            new BonusCommand(),
            new RecipeCommand(),
            new InvClearCommand(),
            new MiningToolsCommand(),
            new ShopCommand(),
            new PlayerCommand(),
            new FlyCommand(),
        ]);
    }

    private function loadTos() {
        $resource = $this->getResource(self::TOS_FILE);
        self::$tos = stream_get_contents($resource);
        fclose($resource);
    }

    public function onDisable(): void {
        $this->getLogger()->info("pluginが正常に停止しました。");
    }
}