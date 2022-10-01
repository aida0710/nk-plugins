<?php

namespace deceitya\repair\command;

use Deceitya\MiningLevel\MiningLevelAPI;
use deceitya\repair\form\RepairForm;
use Exception;
use lazyperson710\core\packet\SendForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\Durable;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\ItemIds;
use pocketmine\item\TieredTool;
use pocketmine\item\ToolTier;
use pocketmine\player\Player;

class RepairCommand extends Command {

    public function __construct() {
        parent::__construct("repair", "どこでも修繕formを開く(80レベル以上)");
    }

    /**
     * @throws Exception
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!($sender instanceof Player)) {
            $sender->sendMessage("Please use in server");
            return;
        }
        $player = $sender;
        $item = $player->getInventory()->getItemInHand();
        if (MiningLevelAPI::getInstance()->getLevel($sender) >= 80) {
            if ($item->getId() === ItemIds::ELYTRA) {
                if (!($item instanceof Durable)) {
                    throw new Exception();
                }
                if ($item->getDamage() <= 0) {
                    $player->sendMessage('§bRepair §7>> §c耐久力が減っていない為、修繕することができません');
                    return;
                }
            }
            if (!$item instanceof TieredTool) {
                $player->sendMessage("§bRepair §7>> §c持っているアイテムは修繕することが出来ません");
                return;
            }
            if ($item->getDamage() <= 0) {
                $player->sendMessage('§bRepair §7>> §c耐久力が減っていない為、修繕することができません');
                return;
            }
            if ($item->hasEnchantment(VanillaEnchantments::PUNCH())) {
                $player->sendMessage('§bRepair §7>> §c衝撃エンチャントが付与されている為、修繕することが出来ません');
                return;
            }
            if ($item->getNamedTag()->getTag('MiningTools_3') !== null) {
                if ($item->getTier() === ToolTier::DIAMOND()) {
                    $player->sendMessage('§bRepair §7>> §cこのアイテムは修繕が出来なくなりました');
                    return;
                }
            }
            if ($item->getNamedTag()->getTag('4mining') !== null) {
                if ($item->getTier() === ToolTier::DIAMOND()) {
                    $player->sendMessage('§bRepair §7>> §cこのアイテムは修繕が出来なくなりました');
                    return;
                }
            }
            $itemIds = $item->getId();
            if ($itemIds >= 1000) {
                $player->sendMessage('§bRepair §7>> §cこのアイテムは修繕することが出来ません');
                return;
            }
            $level = 5;
            foreach ($item->getEnchantments() as $enchant) {
                $level += 8 + $enchant->getLevel();
            }
            $mode = "command";
            SendForm::Send($player, (new RepairForm($level, $item, $mode)));
        } else {
            $sender->sendMessage("§bRepair §7>> §cRepairコマンドは80レベル以上でないと開くことが出来ません。通常はかなとこをスニークタップすることで修繕出来ます");
        }
    }
}