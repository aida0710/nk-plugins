<?php

namespace deceitya\efenshop;

use onebone\economyapi\EconomyAPI;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\data\bedrock\EnchantmentIds;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;
use pocketmine\network\mcpe\protocol\ModalFormResponsePacket;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase implements Listener {

    const EFFECT_FORM_ID = 436246;
    const ENCHANT_FORM_ID = 96242;

    protected function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
        if (!($sender instanceof Player)) {
            $sender->sendMessage("サーバー内で使用してください。");
            return true;
        }
        if ($label == 'ef') {
            $form = [
                "type" => "custom_form",
                "title" => "EffectShop",
                "content" => [
                    [
                        "type" => "input",
                        "text" => "効果時間(分)をご記入ください",
                        "placeholder" => "1分から500分までの整数を入力",
                        "default" => null
                    ],
                    [
                        "type" => "dropdown",
                        "text" => "§7Enchant名 | Lv2(付与されるレベル) | 1m/800円(1分の値段)",
                        "options" => [
                            "タップして付与したいエフェクトを選択してください",
                            "採掘速度上昇 | Lv2 | 1m/800円", //1#HASTE
                            "移動速度上昇 | Lv2 | 1m/250円", //1#HASTE
                            "再生速度上昇 | Lv2 | 1m/1600円", //3#REGENERATION
                            "暗視 | Lv1 | 1m/50円" //6#NIGHT_VISION
                        ],
                        "default" => 0
                    ]
                ]
            ];
            $pk = new ModalFormRequestPacket();
            $pk->formId = self::EFFECT_FORM_ID;
            $pk->formData = json_encode($form);
            $sender->getNetworkSession()->sendDataPacket($pk);
        } elseif ($label == 'ven') {
            $form = [
                "type" => "custom_form",
                "title" => "EnchantShop",
                "content" => [
                    [
                        "type" => "input",
                        "text" => "持ってるアイテムにエンチャントがつきます\n付与したいレベルをご記入ください",
                        "placeholder" => "1lvから5lvまでの整数を入力",
                        "default" => null
                    ],
                    [
                        "type" => "dropdown",
                        "text" => "§7Enchant名 | 2lv以下(最大レベル) | Lv/1レベルごとの値段",
                        "options" => [
                            "タップして付与したいエンチャントを選択してください",
                            "ダメージ増加 | 5lv以下 | Lv/3000円",
                            "効率強化 | 5lv以下 | Lv/5000円",
                            "シルクタッチ | 1lv以下 | Lv/15000円",
                            "幸運 | 3lv以下 | Lv/30000円",
                            "耐久力 | 3lv以下 | Lv/10000円",
                            "射撃ダメージ増加 | 5lv以下 | Lv/30000円"
                        ],
                        "default" => 0
                    ]
                ]
            ];
            $pk = new ModalFormRequestPacket();
            $pk->formId = self::ENCHANT_FORM_ID;
            $pk->formData = json_encode($form);
            $sender->getNetworkSession()->sendDataPacket($pk);
        }
        return true;
    }

    public function onReceive(DataPacketReceiveEvent $event) {
        $pk = $event->getPacket();
        if ($pk instanceof ModalFormResponsePacket) {
            $player = $event->getOrigin()->getPlayer();
            $id = $pk->formId;
            $data = json_decode($pk->formData);
            if (!($id == self::EFFECT_FORM_ID || $id == self::ENCHANT_FORM_ID)) {
                return;
            }
            if ($data === null) {
                $player->sendMessage("§bEnEfShop §7>> §bキャンセルしました。");
                return;
            }
            if (!$this->isInteger($data[0]) || ((int)$data[0]) < 1) {
                $player->sendMessage("§bEnEfShop §7>> §c1以上の整数を入力してください");
                return;
            }
            $data[0] = (int)$data[0];
            if ($id == self::EFFECT_FORM_ID) {
                if ($data[0] > 500) {
                    $player->sendMessage("§bEnEfShop §7>> §c値は500以下にして下さい。");
                    return;
                }
                switch ($data[1]) {
                    case 0:
                        $need = 99999999999999999999999999999999999999999999999999999 * $data[0];
                        if (EconomyAPI::getInstance()->myMoney($player) < $need) {
                            $player->sendMessage("§bEnEfShop §7>> §c付与したいエフェクトを選択してください");
                            return;
                        }
                        $effect = new EffectInstance(VanillaEffects::HASTE(), $data[0] * 20 * 60, 1, false);
                        $player->getEffects()->add($effect);
                        EconomyAPI::getInstance()->reduceMoney($player, $need);
                        $player->sendMessage("§bEnEfShop §7>> §a{$need}円で採掘速度上昇を{$data[0]}分間付与しました");
                        break;
                    case 1:
                        $need = 800 * $data[0];
                        $this->isMoney($player, $need);
                        $effect = new EffectInstance(VanillaEffects::HASTE(), $data[0] * 20 * 60, 1, false);
                        $player->getEffects()->add($effect);
                        EconomyAPI::getInstance()->reduceMoney($player, $need);
                        $player->sendMessage("§bEnEfShop §7>> §a{$need}円で採掘速度上昇を{$data[0]}分間付与しました");
                        break;
                    case 2:
                        $need = 150 * $data[0];
                        $this->isMoney($player, $need);
                        $effect = new EffectInstance(VanillaEffects::SPEED(), $data[0] * 20 * 60, 1, false);
                        $player->getEffects()->add($effect);
                        EconomyAPI::getInstance()->reduceMoney($player, $need);
                        $player->sendMessage("§bEnEfShop §7>> §a{$need}円で移動速度上昇を{$data[0]}分間付与しました");
                        break;
                    case 3:
                        $need = 1600 * $data[0];
                        $this->isMoney($player, $need);
                        $effect = new EffectInstance(VanillaEffects::REGENERATION(), $data[0] * 20 * 60, 2, true);
                        $player->getEffects()->add($effect);
                        EconomyAPI::getInstance()->reduceMoney($player, $need);
                        $player->sendMessage("§bEnEfShop §7>> §a{$need}円で再生速度上昇を{$data[0]}分間付与しました");
                        break;
                    case 4:
                        $need = 50 * $data[0];
                        $this->isMoney($player, $need);
                        $effect = new EffectInstance(VanillaEffects::NIGHT_VISION(), $data[0] * 20 * 60, 0, false);
                        $player->getEffects()->add($effect);
                        EconomyAPI::getInstance()->reduceMoney($player, $need);
                        $player->sendMessage("§bEnEfShop §7>> §a{$need}円で暗視を{$data[0]}分間付与しました");
                        break;
                    default:
                        break;
                }
            }
            if ($id == self::ENCHANT_FORM_ID) {
                if ($data[0] > 5) {
                    $player->sendMessage("§bEnEfShop §7>> §c値は5以下にして下さい。");
                    return;
                }
                
                switch ($data[1]) {
                    case 0: 
                        $need = 9999999999999999999999999999999999999999999999999 * $data[0];
                        if (EconomyAPI::getInstance()->myMoney($player) < $need) {
                            $player->sendMessage("§bEnEfShop §7>> §c付与したいエンチャントを選択してください");
                            return;
                        }
                        $item = $player->getInventory()->getItemInHand();
                        $this->isHandInItem($player, $item);
                        $enchant = new EnchantmentInstance(VanillaEnchantments::SHARPNESS(), $data[0]);
                        $item->addEnchantment($enchant);
                        EconomyAPI::getInstance()->reduceMoney($player, $need);
                        $player->getInventory()->setItemInHand($item);
                        $player->sendMessage("§bEnEfShop §7>> §aダメージ増加を{$data[0]}レベル、{$need}円で付与しました");
                        break;
                    case 1: 
                        $need = 3000 * $data[0];
                        $this->isMoney($player, $need);
                        $item = $player->getInventory()->getItemInHand();
                        $this->isHandInItem($player, $item);
                        $enchant = new EnchantmentInstance(VanillaEnchantments::SHARPNESS(), $data[0]);
                        $item->addEnchantment($enchant);
                        EconomyAPI::getInstance()->reduceMoney($player, $need);
                        $player->getInventory()->setItemInHand($item);
                        $player->sendMessage("§bEnEfShop §7>> §aダメージ増加を{$data[0]}レベル、{$need}円で付与しました");
                        break;
                    case 2: 
                        $need = 5000 * $data[0];
                        $this->isMoney($player, $need);
                        $item = $player->getInventory()->getItemInHand();
                        $this->isHandInItem($player, $item);
                        $enchant = new EnchantmentInstance(VanillaEnchantments::EFFICIENCY(), $data[0]);
                        $item->addEnchantment($enchant);
                        EconomyAPI::getInstance()->reduceMoney($player, $need);
                        $player->getInventory()->setItemInHand($item);
                        $player->sendMessage("§bEnEfShop §7>> §a効率強化を{$data[0]}レベル、{$need}円で付与しました");
                        break;
                    case 3: 
                        $need = 15000 * $data[0];
                        $this->isMoney($player, $need);
                        $item = $player->getInventory()->getItemInHand();
                        $this->isHandInItem($player, $item);
                        if ($item->hasEnchantment(EnchantmentIdMap::getInstance()->fromId(EnchantmentIds::FORTUNE))) {
                            $player->sendMessage('§bEnEfShop §7>> §c幸運がついているため、シルクタッチはつけられません');
                            return;
                        }
                        $enchant = new EnchantmentInstance(VanillaEnchantments::SILK_TOUCH(), $data[0]);
                        $item->addEnchantment($enchant);
                        EconomyAPI::getInstance()->reduceMoney($player, $need);
                        $player->getInventory()->setItemInHand($item);
                        $player->sendMessage("§bEnEfShop §7>> §aシルクタッチを{$data[0]}レベル、{$need}円で付与しました");
                        break;
                    case 4: 
                        $need = 30000 * $data[0];
                        $this->isMoney($player, $need);
                        $item = $player->getInventory()->getItemInHand();
                        $this->isHandInItem($player, $item);
                        if ($item->hasEnchantment(VanillaEnchantments::SILK_TOUCH())) {
                            $player->sendMessage('§bEnEfShop §7>> §cシルクタッチエンチャントがついているため、幸運はつけられません');
                            return;
                        }
                        if ($data[0] >= 4) {
                            $player->sendMessage("§bCustomEncchant §7>> §c4レベル以上のエンチャントを購入する事はできません。付与しようとしたレベル " . $data[0] . "§r");
                            return;
                        }
                        $enchant = new EnchantmentInstance(EnchantmentIdMap::getInstance()->fromId(EnchantmentIds::FORTUNE), $data[0]);
                        $item->addEnchantment($enchant);
                        EconomyAPI::getInstance()->reduceMoney($player, $need);
                        $player->getInventory()->setItemInHand($item);
                        $player->sendMessage("§bEnEfShop §7>> §a幸運を{$data[0]}レベル、{$need}円で付与しました");
                        break;
                    case 5: 
                        $need = 10000 * $data[0];
                        $this->isMoney($player, $need);
                        $item = $player->getInventory()->getItemInHand();
                        $this->isHandInItem($player, $item);
                        if ($data[0] >= 4) {
                            $player->sendMessage("§bCustomEncchant §7>> §c4レベル以上のエンチャントを購入する事はできません。付与しようとしたレベル " . $data[0] . "§r");
                            return;
                        }
                        $enchant = new EnchantmentInstance(VanillaEnchantments::UNBREAKING(), $data[0]);
                        $item->addEnchantment($enchant);
                        EconomyAPI::getInstance()->reduceMoney($player, $need);
                        $player->getInventory()->setItemInHand($item);
                        $player->sendMessage("§bEnEfShop §7>> §a耐久力を{$data[0]}レベル、{$need}円で付与しました");
                        break;
                    case 6: 
                        $need = 30000 * $data[0];
                        $item = $player->getInventory()->getItemInHand();
                        $this->isMoney($player, $need);
                        $this->isHandInItem($player, $item);
                        $enchant = new EnchantmentInstance(VanillaEnchantments::POWER(), $data[0]);
                        $item->addEnchantment($enchant);
                        EconomyAPI::getInstance()->reduceMoney($player, $need);
                        $player->getInventory()->setItemInHand($item);
                        $player->sendMessage("§bEnEfShop §7>> §a射撃ダメージ増加を{$data[0]}レベル、{$need}円で付与しました");
                        break;
                    default:
                        break;
                }
            }
        }
    }

    private function isInteger($input): bool {
        return (ctype_digit(strval($input)));
    }

    public function isMoney(Player $player, int $need) {
        if (EconomyAPI::getInstance()->myMoney($player) < $need) {
            $player->sendMessage("§bEnEfShop §7>> §cお金が足りません");
            return;
        }
    }

    public function isHandInItem(Player $player, $item) {
        if ($item->isNull()) {
            $player->sendMessage("§bEnEfShop §7>> §cアイテムを持ってください");
            return;
        }
    }
}
