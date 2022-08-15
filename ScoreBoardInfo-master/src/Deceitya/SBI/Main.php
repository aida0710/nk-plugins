<?php

namespace Deceitya\SBI;

use bbo51dog\bboform\element\Button;
use bbo51dog\bboform\element\ClosureButton;
use bbo51dog\bboform\form\SimpleForm;
use Deceitya\SBI\mode\Mode;
use Deceitya\SBI\mode\ModeList;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\player\Player;
use lazyperson710\core\packet\SendForm;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

class Main extends PluginBase implements Listener {

    protected function onEnable(): void {
        Database::init($this->getDataFolder() . "SbiData.yml");
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    protected function onDisable(): void {
        Database::getInstance()->save();
    }

    public function onJoin(PlayerJoinEvent $event) {
        $player = $event->getPlayer();
        if (!Database::getInstance()->exists($player)) {
            Database::getInstance()->setMode($player, Mode::NORMAL);
        }
        $this->getScheduler()->scheduleRepeatingTask(new DisplayTask($player), 20);
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        if (!$sender instanceof Player) {
            return false;
        }
        $form = (new SimpleForm())
            ->setTitle("Select Status Mode")
            ->setText("表示形式を選択してください");
        foreach (ModeList::getInstance()->getAll() as $mode) {
            $form->addElement(new ClosureButton($mode->getName(), null, function (Player $player, Button $button) use ($mode) {
                Database::getInstance()->setMode($player, $mode->getId());
                $player->sendTip(TextFormat::AQUA . "MyStatus §7>> §a表示状態を『{$mode->getName()}』にしました");
            }));
        }
        SendForm::Send($sender, ($form));
        return true;
    }
}
