<?php

declare(strict_types = 1);
namespace lazyperson0710\Gacha\database;

use lazyperson0710\Gacha\Main;
use pocketmine\block\VanillaBlocks;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\VanillaItems;

class GachaItemAPI {

    private static GachaItemAPI $instance;
    public const Category = [
        1 => "常駐ガチャ",
        2 => "Coming Soon...",
    ];
    public array $categoryCost = [];
    public array $rankProbability = [];
    public array $gachaItems = [];

    public function init(): void {
        ## Gacha Category
        $this->categorySettingRegister("常駐ガチャ", 0, 1, 60, 33, 5, 1.6, 0.3, 0.1);
        $this->categorySettingRegister("Coming Soon...", 10000000000, 0, 50, 10, 10, 10, 10, 10);
        ## 未使用Category用テスト用景品
        $this->dischargeItemRegister("Coming Soon...", "C", VanillaBlocks::STONE()->asItem(), 1, "Rank C", "テスト用の景品になります", [], [], []);
        $this->dischargeItemRegister("Coming Soon...", "UC", VanillaBlocks::STONE()->asItem(), 1, "Rank UC", "テスト用の景品になります", [], [], []);
        $this->dischargeItemRegister("Coming Soon...", "R", VanillaBlocks::STONE()->asItem(), 1, "Rank R", "テスト用の景品になります", [], [], []);
        $this->dischargeItemRegister("Coming Soon...", "SR", VanillaBlocks::STONE()->asItem(), 1, "Rank SR", "テスト用の景品になります", [], [], []);
        $this->dischargeItemRegister("Coming Soon...", "SSR", VanillaBlocks::STONE()->asItem(), 1, "Rank SSR", "テスト用の景品になります", [], [], []);
        $this->dischargeItemRegister("Coming Soon...", "L", VanillaBlocks::STONE()->asItem(), 1, "Rank L", "テスト用の景品になります", [], [], []);
        ## C Rank
        $this->dischargeItemRegister("常駐ガチャ", "C", VanillaItems::NETHER_QUARTZ(), 1, "はずれ石", "一枚のTicketに変換出来ます\n\n/ticket", [], [], ["ExchangeTicket"]);
        $this->dischargeItemRegister("常駐ガチャ", "C", VanillaItems::NETHER_STAR(), 1, "通貨アイテム", "/shop->LevelShop1->通貨アイテム\nから換金することが可能です", [], [], []);
        $this->dischargeItemRegister("常駐ガチャ", "C", VanillaItems::STEAK(), 1, "ステーキ", "食料です！！！！！！！！！！！！", [], [], []);
        $this->dischargeItemRegister("常駐ガチャ", "C", VanillaItems::PUMPKIN_PIE(), 1, "かぼちゃのパイ", "とっても甘いかぼちゃのパイ", [], [], []);
        $this->dischargeItemRegister("常駐ガチャ", "C", VanillaItems::LIGHT_BLUE_DYE(), 1, "水色のごみ", "これが本当のごみ", [], [], []);
        $this->dischargeItemRegister("常駐ガチャ", "C", VanillaItems::BONE(), 1, "たたの骨", "成人した人間の骨って206本もあるんだって！", [], [], []);
        $this->dischargeItemRegister("常駐ガチャ", "C", VanillaItems::BONE(), 1, "びーぼの骨", "びーぼがいつも握りしめてるお気に入りの骨", [VanillaEnchantments::PUNCH(), VanillaEnchantments::MENDING()], [1, 1], []);
        $this->dischargeItemRegister("常駐ガチャ", "C", VanillaItems::NETHER_QUARTZ(), 1, "ぴかぴかした石", "きれいですね", [VanillaEnchantments::UNBREAKING()], [1], []);
        $this->dischargeItemRegister("常駐ガチャ", "C", VanillaItems::COOKED_SALMON(), 1, "かき氷", "イチゴ味！", [], [], []);
        ## UC Rank
        $this->dischargeItemRegister("常駐ガチャ", "UC", VanillaItems::COOKED_MUTTON(), 1, "猫用チュール", "人間用ではない", [], [], []);
        $this->dischargeItemRegister("常駐ガチャ", "UC", VanillaItems::COOKED_CHICKEN(), 1, "犬用チュール", "人間用ではない", [], [], []);
        $this->dischargeItemRegister("常駐ガチャ", "UC", VanillaItems::COOKED_PORKCHOP(), 1, "アメリカンドック", "アメリカンドッグはソーセージに串を刺し、小麦粉などで作った衣をつけて油で揚げた食品。 アメリカで普及しているコーンミールの生地を使ったコーン・ドッグを改良したものであり、名称は和製英語である。\n\n引用 Wiki", [], [], []);
        $this->dischargeItemRegister("常駐ガチャ", "UC", VanillaBlocks::GLASS()->asItem(), 1, "エンジェルブロック", "自分の下にブロックを設置する", [], [], ["AirBlock"]);
        $this->dischargeItemRegister("常駐ガチャ", "UC", VanillaItems::EXPERIENCE_BOTTLE(), 16, "魔剤セット", "ちょっとずつ飲もう。。。", [], [], []);
        $this->dischargeItemRegister("常駐ガチャ", "UC", VanillaBlocks::TNT()->asItem(), 1, "爆発物", "設置すると爆発します", [], [], ["ExplosionBlock"]);
        $this->dischargeItemRegister("常駐ガチャ", "UC", VanillaItems::EGG(), 1, "温泉卵", "温まってるので中身はない", [], [], []);
        //$this->dischargeItemRegister("常駐ガチャ", "SR", items, 1, "EffectCleaner", "現在付与されているエフェクトを全て削除します", [], [], []);
        ## R Rank
        $this->dischargeItemRegister("常駐ガチャ", "R", VanillaItems::COOKED_FISH(), 1, "ラムネ", "美味しいよね", [], [], []);
        $this->dischargeItemRegister("常駐ガチャ", "R", VanillaItems::COOKED_RABBIT(), 1, "モンスター", "カフェイン摂取キモチエェェェ！", [], [], []);
        $this->dischargeItemRegister("常駐ガチャ", "R", VanillaItems::STONE_SHOVEL(), 1, "鋭利なシャベル", "ゾンビを倒すために使うものではない", [VanillaEnchantments::SHARPNESS()], [5], []);
        $this->dischargeItemRegister("常駐ガチャ", "R", VanillaItems::PAPER(), 1, "バスタオル", "常時濡れてるので叩かれると若干痛い", [VanillaEnchantments::SHARPNESS()], [1], []);
        $this->dischargeItemRegister("常駐ガチャ", "R", VanillaItems::IRON_PICKAXE(), 1, "溶鉱炉付きのつるはし", "一部のブロックを採掘時に焼いちゃうぞ！うおうお！", [VanillaEnchantments::SILK_TOUCH(), VanillaEnchantments::PUNCH()], [1, 1], ["BlastFurnacePickaxe"]);
        $this->dischargeItemRegister("常駐ガチャ", "R", VanillaBlocks::POPPY()->asItem(), 1, "§cﾌｧｲﾔｰﾌﾗﾜｰ", "ふぁいやー！！！", [VanillaEnchantments::FIRE_ASPECT()], [1], []);
        $this->dischargeItemRegister("常駐ガチャ", "R", ItemFactory::getInstance()->get(751), 1, "落下ダメージ完全防御ブーツ", "着用していると落下ダメージから自分を守ってくれる", [], [], ["DefensiveStone"]);
        //$this->dischargeItemRegister("常駐ガチャ", "SR", items, 1, "金のリンゴ", "少しだけ掘る気になるリンゴ", [], [], []);
        ## SR Rank
        $this->dischargeItemRegister("常駐ガチャ", "SR", VanillaBlocks::MONSTER_SPAWNER()->asItem(), 1, "レベルブロック", "れぇべぇるぅ\n破壊するとバニラ経験値がもらえます", [], [], []);
        $this->dischargeItemRegister("常駐ガチャ", "SR", VanillaItems::DIAMOND_PICKAXE(), 1, "黒曜石ブレイカー", "黒曜石だけを徹底的に破壊する", [VanillaEnchantments::PUNCH()], [1], ["ObsidianBreaker"]);
        $this->dischargeItemRegister("常駐ガチャ", "SR", VanillaItems::DIAMOND_HOE(), 1, "グロウストーンブレイカー", "グロウストーンだけを徹底的に破壊する", [VanillaEnchantments::PUNCH()], [1], ["GlowstoneBreaker"]);
        //$this->dischargeItemRegister("常駐ガチャ", "SR", items, 1, "エンチャント金のリンゴ", "とても掘る気になるリンゴ", [], [], []);
        ## SSR Rank
        $this->dischargeItemRegister("常駐ガチャ", "SSR", VanillaItems::FISHING_ROD(), 1, "SuperRod", "どこまでも走れる気がする", [VanillaEnchantments::PUNCH()], [1], []);
        $this->dischargeItemRegister("常駐ガチャ", "SSR", VanillaItems::GOLD_INGOT(), 1, "道具名チェンジャー", "道具、装備の名前を変更することが可能です\n名前を変更したいアイテムを持った状態で/ncコマンドを実行してください", [VanillaEnchantments::PROJECTILE_PROTECTION()], [5], ["ItemNameChangeIngot"]);
        $this->dischargeItemRegister("常駐ガチャ", "SSR", VanillaItems::IRON_INGOT(), 1, "修繕費用代替インゴット", "アイテムの修繕費用(ExpLevel)を代替します", [VanillaEnchantments::MENDING()], [1], ["repairIngot"]);
        $this->dischargeItemRegister("常駐ガチャ", "SSR", VanillaItems::DIAMOND_SHOVEL(), 1, "§a[Gacha]DiamondMiningShovel", "がちゃ仕様の特殊範囲破壊シャベル\n\n自由にエンチャントしたり修繕することが可能です", [], [], ["MiningTools_3", "gacha_mining"]);
        $this->dischargeItemRegister("常駐ガチャ", "SSR", VanillaItems::DIAMOND_PICKAXE(), 1, "§a[Gacha]DiamondMiningPickaxe", "がちゃ仕様の特殊範囲破壊ピッケル\n\n自由にエンチャントしたり修繕することが可能です", [], [], ["MiningTools_3", "gacha_mining"]);
        $this->dischargeItemRegister("常駐ガチャ", "SSR", VanillaItems::DIAMOND_AXE(), 1, "§a[Gacha]DiamondMiningAxe", "がちゃ仕様の特殊範囲破壊アックス\n\n自由にエンチャントしたり修繕することが可能です", [], [], ["MiningTools_3", "gacha_mining"]);
        ## L Rank
        $this->dischargeItemRegister("常駐ガチャ", "L", ItemFactory::getInstance()->get(-302), 1, "MiningToolRangeExpansion", "Netherite MiningToolsの範囲を強化できます/mt", [], [], ["MiningToolsRangeCostItem"]);
        $this->dischargeItemRegister("常駐ガチャ", "L", ItemFactory::getInstance()->get(174), 1, "MiningToolEnchantExpansion", "Netherite MiningToolsのエンチャントを強化できます/mt", [], [], ["MiningToolsEnchantCostItem"]);
        $this->dischargeItemRegister("常駐ガチャ", "L", VanillaBlocks::LAPIS_LAZULI()->asItem(), 1, "EnablingMiningSettingExpansion", "MiningToolsの設定機能を強化できます/mt", [], [], ["EnablingMiningSettingItem"]);
    }

    private function categorySettingRegister(string $category, int $moneyCost, int $ticketCost, float $C, float $UC, float $R, float $SR, float $SSR, float $L): void {
        $this->categoryCost[$category][] = ["moneyCost" => $moneyCost, "ticketCost" => $ticketCost];
        $this->rankProbability[$category][] = ["C" => $C, "UC" => $UC, "R" => $R, "SR" => $SR, "SSR" => $SSR, "L" => $L];
    }

    private function dischargeItemRegister(string $category, string $rank, Item $item, int $count, string $name, string $lore, array $enchants, array $level, array $nbt): void {
        if (!$this->checkFunctionArgument($category, $rank, $enchants, $level)) Main::getInstance()->getServer()->getPluginManager()->disablePlugin(Main::getInstance());
        $this->gachaItems[$category][$rank][] = ["item" => $item, "count" => $count, "name" => $name, "lore" => $lore, "enchants" => $enchants, "level" => $level, "nbt" => $nbt];
    }

    private function checkFunctionArgument(string $category, string $rank, array $enchants, array $level): bool {
        if (!in_array($category, self::Category)) {
            Main::getInstance()->getLogger()->critical("Gacha : アイテム登録時にカテゴリーに登録されていないガチャが入力された為プラグインを停止します");
            return false;
        }
        if (!in_array($rank, ["C", "UC", "R", "SR", "SSR", "L"])) {
            Main::getInstance()->getLogger()->critical("Gacha : アイテム登録時にランクに不正の値が入力されていた為プラグインを停止します");
            return false;
        }
        if (count($enchants) !== count($level)) {
            Main::getInstance()->getLogger()->critical("Gacha : アイテム登録時にエンチャントとレベルの数が一致していない為プラグインを停止します");
            return false;
        }
        return true;
    }

    /**
     * @return GachaItemAPI
     */
    public static function getInstance(): GachaItemAPI {
        if (!isset(self::$instance)) {
            self::$instance = new GachaItemAPI();
        }
        return self::$instance;
    }

}