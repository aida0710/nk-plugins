<?php

declare(strict_types = 0);

namespace xenialdan\apibossbar;

use GlobalLogger;
use InvalidArgumentException;
use lazyperson0710\PlayerSetting\object\PlayerSettingPool;
use lazyperson0710\PlayerSetting\object\settings\normal\BossBarColorSetting;
use pocketmine\entity\Attribute;
use pocketmine\entity\AttributeFactory;
use pocketmine\entity\AttributeMap;
use pocketmine\entity\Entity;
use pocketmine\network\mcpe\protocol\BossEventPacket;
use pocketmine\network\mcpe\protocol\RemoveActorPacket;
use pocketmine\network\mcpe\protocol\types\entity\EntityMetadataCollection;
use pocketmine\network\mcpe\protocol\types\entity\EntityMetadataFlags;
use pocketmine\network\mcpe\protocol\types\entity\EntityMetadataProperties;
use pocketmine\network\mcpe\protocol\UpdateAttributesPacket;
use pocketmine\player\Player;
use pocketmine\Server;
use function count;
use function max;
use function mb_convert_encoding;
use function min;

class BossBar {

    public ?int $actorId = null;
    protected EntityMetadataCollection $propertyManager;
    /** @var Player[] */
    private array $players = [];
    private string $title = '';
    private string $subTitle = '';
    private AttributeMap $attributeMap;

    /**
     * BossBar constructor.
     * This will not spawn the bar, since there would be no players to spawn it to
     */
    public function __construct() {
        $this->attributeMap = new AttributeMap();
        $this->getAttributeMap()->add(AttributeFactory::getInstance()->mustGet(Attribute::HEALTH)->setMaxValue(100.0)->setMinValue(0.0)->setDefaultValue(100.0));
        $this->propertyManager = new EntityMetadataCollection();
        $this->propertyManager->setLong(EntityMetadataProperties::FLAGS, 0
            ^ 1 << EntityMetadataFlags::SILENT
            ^ 1 << EntityMetadataFlags::INVISIBLE
            ^ 1 << EntityMetadataFlags::NO_AI
            ^ 1 << EntityMetadataFlags::FIRE_IMMUNE);
        $this->propertyManager->setShort(EntityMetadataProperties::MAX_AIR, 400);
        $this->propertyManager->setString(EntityMetadataProperties::NAMETAG, $this->getFullTitle());
        $this->propertyManager->setLong(EntityMetadataProperties::LEAD_HOLDER_EID, -1);
        $this->propertyManager->setFloat(EntityMetadataProperties::SCALE, 0);
        $this->propertyManager->setFloat(EntityMetadataProperties::BOUNDING_BOX_WIDTH, 0.0);
        $this->propertyManager->setFloat(EntityMetadataProperties::BOUNDING_BOX_HEIGHT, 0.0);
    }

    /**
     * @param Player|null $player Only used for DiverseBossBar
     */
    public function getAttributeMap(Player $player = null) : AttributeMap {
        return $this->attributeMap;
    }

    /**
     * The full title as a combination of the title and its subtitle. Automatically fixes encoding issues caused by
     * newline characters
     */
    public function getFullTitle() : string {
        $text = $this->title;
        if (!empty($this->subTitle)) {
            $text .= "\n\n" . $this->subTitle;
        }
        return mb_convert_encoding($text, 'UTF-8');
    }

    /**
     * @param Player[] $players
     */
    public function addPlayers(array $players) : BossBar {
        foreach ($players as $player) {
            $this->addPlayer($player);
        }
        return $this;
    }

    public function addPlayer(Player $player) : BossBar {
        if (isset($this->players[$player->getId()])) return $this;
        #if (!$this->getEntity() instanceof Player) $this->sendSpawnPacket([$player]);
        $this->sendBossPacket([$player]);
        $this->players[$player->getId()] = $player;
        return $this;
    }

    /**
     * @param Player[] $players
     */
    protected function sendBossPacket(array $players) : void {
        $pk = new BossEventPacket();
        $pk->eventType = BossEventPacket::TYPE_SHOW;
        foreach ($players as $player) {
            if (!$player->isConnected()) continue;
            $pk->bossActorUniqueId = $this->actorId ?? $player->getId();
            $player->getNetworkSession()->sendDataPacket($this->addDefaults($player, $pk));
        }
    }

    private function addDefaults(Player $player, BossEventPacket $pk) : BossEventPacket {
        $pk->title = $this->getFullTitle();
        $pk->healthPercent = $this->getPercentage();
        $pk->darkenScreen = false;
        $setting = PlayerSettingPool::getInstance()->getSettingNonNull($player);
        $pk->color = $setting->getSetting(BossBarColorSetting::getName())?->getValue();  //Does not function anyways
        $pk->overlay = 0;                                                                //Neither. Typical for Mojang: Copy-pasted from Java edition
        return $pk;
    }

    public function getPercentage() : float {
        return $this->getAttributeMap()->get(Attribute::HEALTH)->getValue() / 100;
    }

    /**
     * @param Player[] $players
     */
    public function removePlayers(array $players) : BossBar {
        foreach ($players as $player) {
            $this->removePlayer($player);
        }
        return $this;
    }

    /**
     * Removes a single player from this bar.
     * Use
     *
     * @param Player $player
     *
     * @return BossBar @see BossBar::hideFrom() when just removing temporarily to save some performance / bandwidth
     */
    public function removePlayer(Player $player) : BossBar {
        if (!isset($this->players[$player->getId()])) {
            GlobalLogger::get()->debug('Removed player that was not added to the boss bar (' . $this . ')');
            return $this;
        }
        $this->sendRemoveBossPacket([$player]);
        unset($this->players[$player->getId()]);
        return $this;
    }

    /**
     * @param Player[] $players
     */
    protected function sendRemoveBossPacket(array $players) : void {
        $pk = new BossEventPacket();
        $pk->eventType = BossEventPacket::TYPE_HIDE;
        foreach ($players as $player) {
            if (!$player->isConnected()) continue;
            $pk->bossActorUniqueId = $this->actorId ?? $player->getId();
            $player->getNetworkSession()->sendDataPacket($pk);
        }
    }

    /**
     * Removes all players from this bar
     */
    public function removeAllPlayers() : BossBar {
        foreach ($this->getPlayers() as $player) $this->removePlayer($player);
        return $this;
    }

    /**
     * @return Player[]
     */
    public function getPlayers() : array {
        return $this->players;
    }

    /**
     * The text above the bar
     */
    public function getTitle() : string {
        return $this->title;
    }

    /**
     * Text above the bar. Can be empty. Should be single-line
     */
    public function setTitle(string $title = '') : BossBar {
        $this->title = $title;
        $this->sendBossTextPacket($this->getPlayers());
        return $this;
    }

    /**
     * @param Player[] $players
     */
    protected function sendBossTextPacket(array $players) : void {
        $pk = new BossEventPacket();
        $pk->eventType = BossEventPacket::TYPE_TITLE;
        $pk->title = $this->getFullTitle();
        foreach ($players as $player) {
            if (!$player->isConnected()) continue;
            $pk->bossActorUniqueId = $this->actorId ?? $player->getId();
            $player->getNetworkSession()->sendDataPacket($pk);
        }
    }

    public function getSubTitle() : string {
        return $this->subTitle;
    }

    /**
     * Optional text below the bar. Can be empty
     */
    public function setSubTitle(string $subTitle = '') : BossBar {
        $this->subTitle = $subTitle;
        #$this->sendEntityDataPacket($this->getPlayers());
        $this->sendBossTextPacket($this->getPlayers());
        return $this;
    }

    /**
     * @param float $percentage 0-1
     */
    public function setPercentage(float $percentage) : BossBar {
        $percentage = (float) min(1.0, max(0.0, $percentage));
        $this->getAttributeMap()->get(Attribute::HEALTH)->setValue($percentage * $this->getAttributeMap()->get(Attribute::HEALTH)->getMaxValue(), true, true);
        #$this->sendAttributesPacket($this->getPlayers());
        $this->sendBossHealthPacket($this->getPlayers());
        return $this;
    }

    /**
     * @param Player[] $players
     */
    protected function sendBossHealthPacket(array $players) : void {
        $pk = new BossEventPacket();
        $pk->eventType = BossEventPacket::TYPE_HEALTH_PERCENT;
        $pk->healthPercent = $this->getPercentage();
        foreach ($players as $player) {
            if (!$player->isConnected()) continue;
            $pk->bossActorUniqueId = $this->actorId ?? $player->getId();
            $player->getNetworkSession()->sendDataPacket($pk);
        }
    }

    /**
     * Hides the bar from all registered players
     */
    public function hideFromAll() : void {
        $this->hideFrom($this->getPlayers());
    }

    /**
     * tod0: nly registered players validation
     * Hides the bar from the specified players without removing it.
     * Useful when saving some bandwidth or when you'd like to keep the entity
     *
     * @param Player[] $players
     */
    public function hideFrom(array $players) : void {
        $pk = new BossEventPacket();
        $pk->eventType = BossEventPacket::TYPE_HIDE;
        foreach ($players as $player) {
            if (!$player->isConnected()) continue;
            $pk->bossActorUniqueId = $this->actorId ?? $player->getId();
            $player->getNetworkSession()->sendDataPacket($this->addDefaults($player, $pk));
        }
    }

    /**
     * Displays the bar to all registered players
     */
    public function showToAll() : void {
        $this->showTo($this->getPlayers());
    }

    /**
     * tod0: Only registered players validation
     * Displays the bar to the specified players
     *
     * @param Player[] $players
     */
    public function showTo(array $players) : void {
        $pk = new BossEventPacket();
        $pk->eventType = BossEventPacket::TYPE_SHOW;
        foreach ($players as $player) {
            if (!$player->isConnected()) continue;
            $pk->bossActorUniqueId = $this->actorId ?? $player->getId();
            $player->getNetworkSession()->sendDataPacket($this->addDefaults($player, $pk));
        }
    }

    /**
     * @param bool $removeEntity Be careful with this. If set to true, the entity will be deleted.
     */
    public function resetEntity(bool $removeEntity = false) : BossBar {
        if ($removeEntity && $this->getEntity() instanceof Entity && !$this->getEntity() instanceof Player) $this->getEntity()->close();
        return $this->setEntity();
    }

    /**
     * @return Entity|null
     */
    public function getEntity() : ?Entity {
        if ($this->actorId === null) return null;
        return Server::getInstance()->getWorldManager()->findEntity($this->actorId);
    }

    /**
     * STILL tod0: SHOULD NOT BE USED YET
     *
     * @return BossBar
     * tod0: use attributes and properties of the custom entity
     */
    public function setEntity(?Entity $entity = null) : BossBar {
        if ($entity instanceof Entity && ($entity->isClosed() || $entity->isFlaggedForDespawn())) throw new InvalidArgumentException("Entity $entity can not be used since its not valid anymore (closed or flagged for despawn)");
        if ($this->getEntity() instanceof Entity && !$entity instanceof Player) $this->getEntity()->flagForDespawn();
        else {
            $pk = new RemoveActorPacket();
            $pk->actorUniqueId = $this->actorId;
            Server::getInstance()->broadcastPackets($this->getPlayers(), [$pk]);
        }
        if ($entity instanceof Entity) {
            $this->actorId = $entity->getId();
            $this->attributeMap = $entity->getAttributeMap();                                 //tod0: try some kind of auto-updating reference
            $this->getAttributeMap()->add($entity->getAttributeMap()->get(Attribute::HEALTH));//tod0:  Auto-update bar for entity? Would be cool, so the api can be used for actual bosses
            $this->propertyManager = $entity->getNetworkProperties();
            if (!$entity instanceof Player) $entity->despawnFromAll();
        } else {
            $this->actorId = Entity::nextRuntimeId();
        }
        #if (!$entity instanceof Player) $this->sendSpawnPacket($this->getPlayers());
        $this->sendBossPacket($this->getPlayers());
        return $this;
    }

    /**
     * @return string
     */
    public function __toString() : string {
        return __CLASS__ . " ID: $this->actorId, Players: " . count($this->players) . ", Title: \"$this->title\", Subtitle: \"$this->subTitle\", Percentage: \"" . $this->getPercentage() . "\"";
    }

    /**
     * @param Player[] $players
     */
    protected function sendAttributesPacket(array $players) : void {//tod0: might not be needed anymore
        if ($this->actorId === null) return;
        $pk = new UpdateAttributesPacket();
        $pk->actorRuntimeId = $this->actorId;
        $pk->entries = $this->getAttributeMap()->needSend();
        Server::getInstance()->broadcastPackets($players, [$pk]);
    }

    /**
     * @return EntityMetadataCollection
     */
    protected function getPropertyManager() : EntityMetadataCollection {
        return $this->propertyManager;
    }

    //tod0: callable on client2server register/unregister request
}
