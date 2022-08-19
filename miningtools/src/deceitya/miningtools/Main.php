<?php

namespace deceitya\miningtools;

use deceitya\miningtools\command\DiamondMiningToolCommand;
use deceitya\miningtools\command\ExpansionMiningToolCommand;
use deceitya\miningtools\command\NetheriteMiningToolCommand;
use deceitya\miningtools\eventListener\BreakEventListener;
use deceitya\miningtools\eventListener\JoinEventListener;
use deceitya\miningtools\setting\MiningToolSettings;
use deceitya\miningtools\task\SettingDataSaveTask;
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

    static array $diamond;
    static array $netherite;

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
        self::$diamond = Main::getInstance()->dataAcquisition("diamond");
        self::$netherite = Main::getInstance()->dataAcquisition("netherite");
        if (!file_exists($this->getDataFolder())) {
            mkdir($this->getDataFolder() . "SettingData.yml");
        }
        $this->getScheduler()->scheduleRepeatingTask(new SettingDataSaveTask(), 20 * 60);
        MiningToolSettings::getInstance()->setCache($this->getDataFolder() . "SettingData.yml");
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
