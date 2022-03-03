<?php

namespace aieuo\itemEditor;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\item\Durable;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\ShortTag;
use pocketmine\network\mcpe\protocol\ModalFormRequestPacket;
use pocketmine\network\mcpe\protocol\ModalFormResponsePacket;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase implements Listener {

    private $forms = [];

    public function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {

        if (!$command->testPermission($sender)) return true;
        if (!$sender instanceof Player){
            return true;
        }
        $item = $sender->getInventory()->getItemInHand();

        if ($item->getId() === 0) {
            $sender->sendMessage("アイテムを持っていません");
            return true;
        }
        $form = [
            "type" => "form",
            "title" => "選択",
            "content" => "§7ボタンを押してください",
            "buttons" => [
                ["text" => "個数"],
                ["text" => "耐久値が<減る/減らない>ようにする"]
            ]
        ];
        $this->sendForm($sender, $form, [$this, "onMenu"]);
        return true;
    }

    public function sendForm(Player $player, $form, $callable = null, ...$datas) {
        while (true) {
            $id = mt_rand(0, 999999999);
            if (!isset($this->forms[$id])) break;
        }
        $this->forms[$id] = [$callable, $datas];
        $pk = new ModalFormRequestPacket();
        $pk->formId = $id;
        $pk->formData = $this->encodeJson($form);
        $player->getNetworkSession()->sendDataPacket($pk);
    }

    public function encodeJson($data) {
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING | JSON_UNESCAPED_UNICODE);
        return $json;
    }

    public function onBreak(BlockBreakEvent $event) {
        $item = $event->getItem();
        $count = 0;
        if (($blocks = $item->getNamedTag("CannotDestroy")) instanceof ListTag) {
            $count = count($blocks);
            $block = $event->getBlock();
            foreach ($blocks as $cannot) {
                if ($cannot->getShort("id") == $block->getId() and $cannot->getShort("damage") == $block->getDamage()) {
                    $event->cancel();
                }
            }
        }
        if ($count === 0 and ($blocks = $item->getNamedTag("CanDestroy")) instanceof ListTag) {
            $block = $event->getBlock();
            if (count($blocks) == 0) return;
            $found = false;
            foreach ($blocks as $cannot) {
                if ($cannot->getShort("id") == $block->getId() and $cannot->getShort("damage") == $block->getDamage()) {
                    $found = true;
                }
            }
            if (!$found) $event->cancel();
        }
    }

    public function onMenu(Player $player, $data) {
        if ($data === null) return;
        $item = $player->getInventory()->getItemInHand();
        switch ($data) {
            case 0:
                $max = $item->getMaxStackSize();
                $form = [
                    "type" => "custom_form",
                    "title" => "個数変更",
                    "content" => [
                        [
                            "type" => "input",
                            "text" => "個数",
                            "default" => (string)$item->getCount(),
                            "placeholder" => "1~" . $max
                        ]
                    ]
                ];
                $this->sendForm($player, $form, [$this, "onChangeCount"]);
                break;
            case 1:
                $unbreakable = ($item instanceof Durable and $item->isUnbreakable());
                $form = [
                    "type" => "form",
                    "title" => "選択",
                    "content" => "今は: 耐久値が減りま" . ($unbreakable ? "せん" : "す"),
                    "buttons" => [
                        ["text" => "耐久値が減るようにする"],
                        ["text" => "耐久値が減らないようにする"]
                    ]
                ];
                $this->sendForm($player, $form, [$this, "onChangeUnbreakable"]);
                break;
            case 2:
                $cannotDestroyBlocks = $item->getNamedTag()->getTag("CannotDestroy") ?? new ListTag(array(["CannotDestroy"], NBT::TAG_Compound));
                $cannotDestroy = [];
                foreach ($cannotDestroyBlocks as $cannot) {
                    $cannotDestroy[] = $cannot->getShort("id") . ":" . $cannot->getShort("damage");
                }
                $canDestroyBlocks = $item->getNamedTag()->getTag("CanDestroy") ?? new ListTag(array(["CanDestroy"], NBT::TAG_Compound));
                $canDestroy = [];
                foreach ($canDestroyBlocks as $can) {
                    $canDestroy[] = $can->getShort("id") . ":" . $can->getShort("damage");
                }
                if (count($cannotDestroy) == 0 and count($canDestroy) == 0) {
                    $content = "全てのブロックが壊せます";
                } elseif (count($cannotDestroy) == 0) {
                    $content = implode(", ", $canDestroy) . " だけが壊せます";
                } else {
                    $content = implode(", ", $cannotDestroy) . " 以外が壊せます";
                }
                $form = [
                    "type" => "form",
                    "title" => "選択",
                    "content" => $content,
                    "buttons" => [
                        ["text" => "壊せないブロック"],
                        ["text" => "壊せるブロック"]
                    ]
                ];
                $this->sendForm($player, $form, [$this, "onSelectDestroy"]);
                break;
        }
    }

    public function onChangeName(Player $player, $data) {
        if ($data === null) return;
        $item = $player->getInventory()->getItemInHand();
        $item->setCustomName($data[0]);
        $player->getInventory()->setItemInHand($item);
        $player->sendMessage("変更しました");
    }

    public function onSelectLore(Player $player, $data, $lore) {
        if ($data === null) return;
        $form = [
            "type" => "custom_form",
            "title" => "説明変更",
            "content" => [
                [
                    "type" => "input",
                    "text" => "説明",
                    "default" => isset($lore[$data]) ? $lore[$data] : ""
                ],
                [
                    "type" => "toggle",
                    "text" => "削除する"
                ]
            ]
        ];
        $this->sendForm($player, $form, [$this, "onChangeLore"], $lore, $data);
    }

    public function onChangeLore(Player $player, $data, $lore, $num) {
        if ($data === null) return;
        $item = $player->getInventory()->getItemInHand();
        if ($data[1]) {
            unset($lore[$num]);
        } else {
            $lore[$num] = $data[0];
        }
        $item->setLore($lore);
        $player->getInventory()->setItemInHand($item);
        $player->sendMessage($data[1] ? "削除しました" : "変更しました");
    }

    public function onChangeDamage(Player $player, $data) {
        if ($data === null) return;
        $item = $player->getInventory()->getItemInHand();
        $max = $item instanceof Durable ? $item->getMaxDurability() : 15;
        $damage = (int)$data[0];
        if ($damage < 0 or $max < $damage) {
            $player->sendMessage("0~" . $max . "の範囲で設定してください");
            return;
        }
        $item->setDamage($damage);
        $player->getInventory()->setItemInHand($item);
        $player->sendMessage("変更しました");
    }

    public function onChangeCount(Player $player, $data) {
        if ($data === null) return;
        $item = $player->getInventory()->getItemInHand();
        $max = $item->getMaxStackSize();
        $count = (int)$data[0];
        if ($count < 1 or $max < $count) {
            $player->sendMessage("1~" . $max . "の範囲で設定してください");
            return;
        }
        $item->setCount($count);
        $player->getInventory()->setItemInHand($item);
        $player->sendMessage("変更しました");
    }

    public function onSelectEnchant(Player $player, $data, $enchantments) {
        if ($data === null) return;
        if (!isset($enchantments[$data])) {
            $form = [
                "type" => "custom_form",
                "title" => "エンチャント",
                "content" => [
                    [
                        "type" => "input",
                        "text" => "エンチャントの名前かid"
                    ],
                    [
                        "type" => "input",
                        "text" => "レベル"
                    ]
                ]
            ];
            $this->sendForm($player, $form, [$this, "onAddEnchant"]);
        } else {
            $enchant = $enchantments[$data];
            $form = [
                "type" => "custom_form",
                "title" => "エンチャント",
                "content" => [
                    [
                        "type" => "label",
                        "text" => $enchant->getType()->getName() . "  id:" . $enchant->getId()
                    ],
                    [
                        "type" => "input",
                        "text" => "レベル",
                        "default" => (string)$enchant->getLevel()
                    ],
                    [
                        "type" => "toggle",
                        "text" => "削除する"
                    ]
                ]
            ];
            $this->sendForm($player, $form, [$this, "onAddEnchant"], $enchant->getId());
        }
    }

    public function onAddEnchant(Player $player, $data, $id = null) {
        if ($data === null) return;
        if ($id === null) $id = $data[0];
        if (is_numeric($id)) {
            $enchant = EnchantmentIdMap::getInstance()->fromId($id);
        } else {
            $enchant = EnchantmentIdMap::getInstance()->fromId($id);
        }
        if (!($enchant instanceof Enchantment)) {
            $player->sendMessage("エンチャントが見つかりません");
            return;
        }
        $level = (int)$data[1];
        $item = $player->getInventory()->getItemInHand();
        $item->addEnchantment(new EnchantmentInstance($enchant, $level));
        $player->getInventory()->setItemInHand($item);
        $player->sendMessage("変更しました");
    }

    public function onChangeUnbreakable(Player $player, $data) {
        if ($data === null) return;
        $item = $player->getInventory()->getItemInHand();
        if (!($item instanceof Durable)) {
            $player->sendMessage("そのアイテムには適用できません");
            return;
        }
        $item->setUnbreakable((bool)$data);
        $player->getInventory()->setItemInHand($item);
        $player->sendMessage("耐久値がへ" . ((bool)$data ? "らない" : "る") . "ようにしました");
    }

    public function onSelectDestroy(Player $player, $data) {
        if ($data === null) return;
        $item = $player->getInventory()->getItemInHand();
        $type = $data == 0 ? "CannotDestroy" : "CanDestroy";
        $blocks = $item->getNamedTag()->getTag($type) ?? new ListTag(array([$type], NBT::TAG_Compound));
        $buttons = [];
        foreach ($blocks as $entry) {
            $buttons[] = ["text" => $entry->getShort("id") . ":" . $entry->getShort("damage")];
        }
        $buttons[] = ["text" => "<追加する>"];
        $form = [
            "type" => "form",
            "title" => "選択",
            "content" => "§7ボタンを押してください",
            "buttons" => $buttons
        ];
        $this->sendForm($player, $form, [$this, "onSelectCanDestroy"], $blocks, $type);
    }

    public function onSelectCanDestroy(Player $player, $data, $blocks, $type) {
        if ($data === null) return;
        $form = [
            "type" => "custom_form",
            "title" => "壊せ" . ($type == "CanDestroy" ? "る" : "ない") . "ブロック",
            "content" => [
                [
                    "type" => "input",
                    "text" => "id",
                    "default" => isset($blocks[$data]) ? $blocks[$data]->getShort("id") . ":" . $blocks[$data]->getShort("damage") : ""
                ],
                [
                    "type" => "toggle",
                    "text" => "削除する"
                ]
            ]
        ];
        $this->sendForm($player, $form, [$this, "onChangeCanDestroy"], $blocks, $data, $type);
    }

    public function onChangeCanDestroy(Player $player, $data, $blocks, $num, $type) {
        if ($data === null) return;
        $item = $player->getInventory()->getItemInHand();
        $ids = explode(":", $data[0]);
        $found = false;
        foreach ($blocks as $k => $entry) {
            if ($entry->getShort("id") == $ids[0] and $entry->getShort("damage") == $ids[1]) {
                if ($data[1]) $blocks->remove($k);
                $found = true;
                break;
            }
        }
        if (!$found and $data[1]) {
            $player->sendMessage("idが見つかりませんでした");
        } elseif (!$found) {
            $blocks->push(new CompoundTag("", [
                new ShortTag("id", (int)$ids[0]),
                new ShortTag("damage", (int)$ids[1])
            ]));
            $player->sendMessage("追加しました");
        } elseif ($data[1]) {
            $player->sendMessage("削除しました");
        } else {
            $player->sendMessage("そのidはすでに追加済みです");
        }
        $item->getNamedTag()->setTag($type, $blocks);
        $player->getInventory()->setItemInHand($item);
    }

    public function Receive(DataPacketReceiveEvent $event) {
        $pk = $event->getPacket();
        $player = $event->getOrigin()->getPlayer();
        if ($pk instanceof ModalFormResponsePacket) {
            if (isset($this->forms[$pk->formId])) {
                $json = str_replace([",]", ",,"], [",\"\"]", ",\"\","], $pk->formData);
                $data = json_decode($json);
                if (is_callable($this->forms[$pk->formId][0])) {
                    call_user_func_array($this->forms[$pk->formId][0], array_merge([$player, $data], $this->forms[$pk->formId][1]));
                }
                unset($this->forms[$pk->formId]);
            }
        }
    }
}