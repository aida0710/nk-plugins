<?php

namespace deceitya\miningtools;

use deceitya\miningtools\command\DiamondMiningToolCommand;
use deceitya\miningtools\command\ExpansionMiningToolCommand;
use deceitya\miningtools\command\NetheriteMiningToolCommand;
use deceitya\miningtools\eventListener\BreakEventListener;
use deceitya\miningtools\eventListener\JoinEventListener;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase implements Listener {

    public const NETHERITE_SHOVEL = 744;
    public const NETHERITE_PICKAXE = 745;
    public const NETHERITE_AXE = 746;

    public const PrefixGreen = "§bMiningTools §7>> §a";
    public const PrefixRed = "§bMiningTools §7>> §c";
    public const PrefixYellow = "§bMiningTools §7>> §e";

    private static self $instance;

    public array $config;
    private array $allData;

    static array $flag = [];

    const PrefixGreen = "§bMiningTools §7>> §a";
    const PrefixRed = "§bMiningTools §7>> §c";
    const PrefixYellow = "§bMiningTools §7>> §e";

    /**
     * @return void
     */
    protected function onEnable(): void {
        $this->saveResource("config.json");
        $this->getServer()->getPluginManager()->registerEvents(new BreakEventListener(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new JoinEventListener(), $this);
        $this->getServer()->getCommandMap()->registerAll("MiningTools", [
            new DiamondMiningToolCommand(),
            new NetheriteMiningToolCommand(),
            new ExpansionMiningToolCommand(),
        ]);
        $this->allData = json_decode(file_get_contents($this->getDataFolder() . "config.json"), true);
    }

    /**
     * @param $tools
     * @return array
     */
    public function dataAcquisition($tools): array {
        return $this->allData[0][$tools];
    }

    /**
     * @return void
     */
    protected function onLoad(): void {
        self::$instance = $this;
    }

    /**
     * @return Main
     */
    public static function getInstance(): Main {
        return self::$instance;
    }

}
