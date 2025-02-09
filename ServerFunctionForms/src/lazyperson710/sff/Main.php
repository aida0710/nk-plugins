<?php

declare(strict_types = 0);

namespace lazyperson710\sff;

use lazyperson710\sff\command\CmdExecutionCommand;
use lazyperson710\sff\command\DonationCommand;
use lazyperson710\sff\command\EnchantCommand;
use lazyperson710\sff\command\InformationCommand;
use lazyperson710\sff\command\InvClearCommand;
use lazyperson710\sff\command\LockCommand;
use lazyperson710\sff\command\PlayerCommand;
use lazyperson710\sff\command\PoliceCommand;
use lazyperson710\sff\command\RecipeCommand;
use lazyperson710\sff\command\TosCommand;
use lazyperson710\sff\listener\CmdListener;
use pocketmine\plugin\PluginBase;
use function fclose;
use function stream_get_contents;

class Main extends PluginBase {

    private const TOS_FILE = 'tos.text';

    private static string $tos;

    public static function getTos() : string {
        return self::$tos;
    }

    public function onEnable() : void {
        $this->loadTos();
        $this->getServer()->getPluginManager()->registerEvents(new CmdListener(), $this);
        $this->getServer()->getCommandMap()->registerAll('sff', [
            new TosCommand(),
            new LockCommand(),
            new EnchantCommand(),
            new RecipeCommand(),
            new InvClearCommand(),
            new PlayerCommand(),
            new DonationCommand(),
            new InformationCommand(),
            new CmdExecutionCommand(),
            new PoliceCommand(),
        ]);
    }

    private function loadTos() {
        $resource = $this->getResource(self::TOS_FILE);
        self::$tos = stream_get_contents($resource);
        fclose($resource);
    }
}
