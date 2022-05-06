<?php

declare(strict_types=1);
namespace JavierLeon9966\VanillaElytra;

use BlockHorizons\Fireworks\Loader as Fireworks;
use JavierLeon9966\VanillaElytra\item\{Elytra, FireworkRocket};
use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\inventory\CreativeInventory;
use pocketmine\item\{ItemFactory, ItemIdentifier, ItemIds};
use pocketmine\network\mcpe\protocol\PlayerActionPacket;
use pocketmine\network\mcpe\protocol\types\entity\EntityMetadataFlags;
use pocketmine\network\mcpe\protocol\types\entity\EntityMetadataProperties;
use pocketmine\network\mcpe\protocol\types\PlayerAction;
use pocketmine\plugin\PluginBase;

final class VanillaElytra extends PluginBase implements Listener {

    public function onLoad(): void {
        $itemFactory = ItemFactory::getInstance();
        $itemFactory->register(new Elytra(new ItemIdentifier(ItemIds::ELYTRA, 0), 'Elytra'), true);
        CreativeInventory::getInstance()->add($itemFactory->get(ItemIds::ELYTRA));
    }

    public function onEnable(): void {
        if (class_exists(Fireworks::class)) {
            $itemFactory = ItemFactory::getInstance();
            $itemFactory->register(new FireworkRocket(new ItemIdentifier(ItemIds::FIREWORKS, 0), 'Firework Rocket'), true);
            CreativeInventory::getInstance()->add($itemFactory->get(ItemIds::FIREWORKS));
        }
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    /**
     * @priority MONITOR
     * @ignoreCancelled
     */
    public function onDataPacketReceive(DataPacketReceiveEvent $event): void {
        $packet = $event->getPacket();
        if ($packet instanceof PlayerActionPacket) {
            $player = $event->getOrigin()->getPlayer();
            $properties = $player->getNetworkProperties();
            switch ($packet->action) {
                case PlayerAction::START_GLIDE:
                    $properties->setGenericFlag(EntityMetadataFlags::GLIDING, true);
                    $player->size->scale(1 / 3);
                    break;
                case PlayerAction::STOP_GLIDE:
                    $properties->setGenericFlag(EntityMetadataFlags::GLIDING, false);
                    $player->size->scale(3);
                    break;
                default:
                    return;
            }
            $player->getNetworkProperties()->setFloat(EntityMetadataProperties::BOUNDING_BOX_HEIGHT, $player->size->getHeight());
        }
    }

    /**
     * @priority MONITOR
     * @ignoreCancelled
     */
    /*
    public function onPlayerMove(PlayerMoveEvent $event): void {
        $player = $event->getPlayer();
        $elytra = $player->getArmorInventory()->getChestplate();
        if ($elytra instanceof Elytra and $player->isSurvival() and $player->getNetworkProperties()->getGenericFlag(EntityMetadataFlags::GLIDING)) {
            if ($this->getServer()->getTick() % 20 === 0 and $elytra->applyDamage(1)) {
                $player->getArmorInventory()->setChestplate($elytra);
            }
            $location = $player->getLocation();
            if ($location->pitch >= -59 and $location->pitch <= 38) {
                $player->resetFallDistance();
            }
        }
    }
    */
}