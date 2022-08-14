<?php

namespace lazyperson710\core\listener;

use lazyperson0710\PlayerSetting\form\SettingListForm;
use lazyperson0710\WorldManagement\form\WarpForm;
use lazyperson710\sff\form\CommandExecutionForm;
use lazyperson710\sff\form\InformationForm;
use lazyperson710\sff\form\TosForm;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\player\Player;

class JoinItemUseEvent implements Listener {

    /**
     * @param PlayerInteractEvent $event
     * @return void
     * @priority LOWEST
     */
    public function onInteract(PlayerInteractEvent $event): void {
        $item = $event->getPlayer()->getInventory()->getItemInHand();
        $rootTag = $item->getNamedTag()->getTag(JoinPlayerEvent::NBT_ROOT);
        if (!($rootTag instanceof CompoundTag)) {
            return;
        }
        $event->cancel();
        $this->onUse($event->getPlayer());
    }

    /**
     * @param PlayerItemUseEvent $event
     * @return void
     * @priority LOWEST
     */
    public function onItemUse(PlayerItemUseEvent $event): void {
        $item = $event->getPlayer()->getInventory()->getItemInHand();
        $rootTag = $item->getNamedTag()->getTag(JoinPlayerEvent::NBT_ROOT);
        if (!($rootTag instanceof CompoundTag)) {
            return;
        }
        $event->cancel();
        $this->onUse($event->getPlayer());
    }

    private function onUse(Player $player) {
        $item = $player->getInventory()->getItemInHand();
        $rootTag = $item->getNamedTag()->getTag(JoinPlayerEvent::NBT_ROOT);
        if (!($rootTag instanceof CompoundTag)) {
            return;
        }
        $idTag = $rootTag->getTag(JoinPlayerEvent::NBT_ID);
        if (!($idTag instanceof IntTag)) {
            return;
        }
        switch ($idTag->getValue()) {
            case JoinPlayerEvent::ID_TOS:
                $player->sendForm(new TosForm());
                break;
            case JoinPlayerEvent::ID_INFORMATION:
                $player->sendForm(new InformationForm());
                break;
            case  JoinPlayerEvent::ID_COMMAND_EXECUTION:
                $player->sendForm(new CommandExecutionForm());
                break;
            case JoinPlayerEvent::ID_WARP:
                $player->sendForm(new WarpForm($player));
                break;
            case JoinPlayerEvent::ID_SETTINGS:
                $player->sendForm(new SettingListForm($player));
                break;
        }
    }
}