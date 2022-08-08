<?php

namespace lazyperson710\sff\listener;

use lazyperson0710\WorldManagement\form\WarpForm;
use lazyperson710\sff\form\CommandExecutionForm;
use lazyperson710\sff\form\InformationForm;
use lazyperson710\sff\form\TosForm;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\player\Player;

class ItemListener implements Listener {

    private const NBT_ROOT = "sff";
    private const NBT_ID = "id";

    private const ID_INFORMATION = 1;
    private const ID_COMMAND_EXECUTION = 2;
    private const ID_WARP = 3;
    private const ID_TOS = 4;

    public function onJoin(PlayerJoinEvent $event) {
        $player = $event->getPlayer();
        if (!$player->hasPlayedBefore()) {
            $tos = ItemFactory::getInstance()->get(ItemIds::BOOK);
            $tos->setCustomName("利用規約本");
            $tos->setLore([
                "lore1" => "タップすると利用規約を表示します",
                "lore2" => "利用規約web版 -> https://www.nkserver.net/tos",
            ]);
            $tos->getNamedTag()->setTag(
                self::NBT_ROOT,
                (new CompoundTag())
                    ->setInt(self::NBT_ID, self::ID_TOS)
            );
            if (!$player->getInventory()->contains($tos) && $player->getInventory()->canAddItem($tos)) {
                $player->getInventory()->addItem($tos);
            }
        }
        $information = ItemFactory::getInstance()->get(ItemIds::PAPER);
        $information->setCustomName("Information");
        $information->setLore([
            "lore1" => "お知らせやサーバーの仕様などが確認できます",
            "lore2" => "公式サイト -> https://www.nkserver.net",
        ]);
        $information->getNamedTag()->setTag(
            self::NBT_ROOT,
            (new CompoundTag())
                ->setInt(self::NBT_ID, self::ID_INFORMATION)
        );
        if (!$player->getInventory()->contains($information) && $player->getInventory()->canAddItem($information)) {
            $player->getInventory()->addItem($information);
        }
        $cookie = ItemFactory::getInstance()->get(ItemIds::COOKIE);
        $cookie->setCustomName("Command Execution");
        $cookie->setLore([
            "lore1" => "サーバーの主なコマンドが簡単に実行できます",
        ]);
        $cookie->getNamedTag()->setTag(
            self::NBT_ROOT,
            (new CompoundTag())
                ->setInt(self::NBT_ID, self::ID_COMMAND_EXECUTION)
        );
        if (!$player->getInventory()->contains($cookie) && $player->getInventory()->canAddItem($cookie)) {
            $player->getInventory()->addItem($cookie);
        }
        $compass = ItemFactory::getInstance()->get(ItemIds::COMPASS);
        $compass->setCustomName("Warp Compass");
        $compass->setLore([
            "lore1" => "サーバー上の他ワールドに移動することができます",
        ]);
        $compass->getNamedTag()->setTag(
            self::NBT_ROOT,
            (new CompoundTag())
                ->setInt(self::NBT_ID, self::ID_WARP)
        );
        if (!$player->getInventory()->contains($compass) && $player->getInventory()->canAddItem($compass)) {
            $player->getInventory()->addItem($compass);
        }
    }

    /**
     * @param PlayerInteractEvent $event
     * @return void
     * @priority LOWEST
     */
    public function onInteract(PlayerInteractEvent $event): void {
        $this->onUse($event->getPlayer());
    }

    /**
     * @param PlayerItemUseEvent $event
     * @return void
     * @priority LOWEST
     */
    public function onItemUse(PlayerItemUseEvent $event): void {
        $this->onUse($event->getPlayer());
    }

    private function onUse(Player $player) {
        $item = $player->getInventory()->getItemInHand();
        $rootTag = $item->getNamedTag()->getTag(self::NBT_ROOT);
        if (!($rootTag instanceof CompoundTag)) {
            return;
        }
        $idTag = $rootTag->getTag(self::NBT_ID);
        if (!($idTag instanceof IntTag)) {
            return;
        }
        switch ($idTag->getValue()) {
            case self::ID_TOS:
                $player->sendForm(new TosForm());
                break;
            case self::ID_INFORMATION:
                $player->sendForm(new InformationForm());
                break;
            case  self::ID_COMMAND_EXECUTION:
                $player->sendForm(new CommandExecutionForm());
                break;
            case self::ID_WARP:
                $player->sendForm(new WarpForm($player));
                break;
        }
    }

}
