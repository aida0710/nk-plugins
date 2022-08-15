<?php

namespace Deceitya\ChestLock\Command;

use Deceitya\ChestLock\Form\ModeForm;
use Deceitya\ChestLock\Main;
use lazyperson710\core\packet\SendForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class ChestCommand extends Command {

    private Main $plugin;

    public function __construct(Main $plugin) {
        parent::__construct('lockc', 'チェストをロック、解除、情報を見る', '/lockc <lock|unlock|info>');
        $this->setPermission('cl.command.lockc');
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, string $label, array $args) {
        if (!$this->plugin->isEnabled()) {
            return false;
        }
        if (!$this->testPermission($sender)) {
            return false;
        }
        if (!($sender instanceof Player)) {
            $sender->sendMessage('§bLock §7>> §cサーバー内で使用してください。');
            return true;
        }
        $form = new ModeForm($this->plugin);
        if (isset($args[0])) {
            $stat = ['lock' => 0, 'unlock' => 1, 'info' => 2];
            if (isset($stat[$args[0]])) {
                $form->handleResponse($sender, $stat[$args[0]]);
                return true;
            } else {
                $sender->sendMessage($this->getDescription());
                return false;
            }
        } else {
            SendForm::Send($sender, ($form));
            return true;
        }
    }
}
