<?php

namespace Deceitya\BonusConvert;

use Deceitya\BonusConvert\Form\MainForm;
use lazyperson710\core\packet\SendForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\ItemFactory;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;

class BonusConvertPlugin extends PluginBase {

    /** @var Convert[] */
    private static array $converts = [];

    /**
     * @return Convert[]
     */
    public static function getConverts(): array {
        return self::$converts;
    }

    public function onEnable(): void {
        $this->saveResource('config.yml');
        $this->reloadConfig();
        foreach ($this->getConfig()->getAll() as $count => $items) {
            $convert = new Convert($count);
            foreach ($items as $v) {
                $data = explode(":", $v);
                $item = ItemFactory::getInstance()->get((int)$data[0], (int)$data[1], (int)$data[2]);
                if ($data[3] !== '') {
                    $item->setCustomName($data[3]);
                }
                if ($data[4] !== '') {
                    $item->setLore([$data[4]]);
                }
                if ($data[5] !== '' && $data[6] !== '') {
                    $item->addEnchantment(new EnchantmentInstance(EnchantmentIdMap::getInstance()->fromId((int)$data[5]), (int)$data[6]));
                }
                $convert->addItem($item);
            }
            self::$converts[] = $convert;
        }
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        if ($sender instanceof Player) {
            SendForm::Send($sender, (new MainForm()));
        }
        return true;
    }
}
