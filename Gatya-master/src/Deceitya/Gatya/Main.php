<?php

namespace Deceitya\Gatya;

use Deceitya\Gatya\Command\GatyaCommand;
use Deceitya\Gatya\Series\SeriesFactory;
use Deceitya\Gatya\Utils\MessageContainer;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase {

    public function onEnable(): void {
        $this->saveResource('series.json');
        MessageContainer::load($this);
        SeriesFactory::init("{$this->getDataFolder()}series.json");
        $this->checkChance();
        $this->registerCommand();
    }

    private function checkChance() {
        foreach (SeriesFactory::getAllSeries() as $series) {
            if ($series->getChanceSum() !== 100.0) {
                $this->getLogger()->warning(MessageContainer::get('chance_invalid', $series->getName()));
                $this->getServer()->getPluginManager()->disablePlugin($this);
                return;
            }
        }
    }

    private function registerCommand() {
        $this->getServer()->getCommandMap()->register('gatya', new GatyaCommand());
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        return true;
    }
}
