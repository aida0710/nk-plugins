<?php

declare(strict_types = 1);
namespace lazyperson0710\Gacha\database;

use Error;
use pocketmine\block\utils\CoralType;
use pocketmine\block\VanillaBlocks;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\VanillaItems;
use function count;
use function in_array;

class GachaItemAPI {

	private static GachaItemAPI $instance;
	public const Category = [
		1 => "常駐ガチャ",
		2 => "Coming Soon...",
	];

	public array $categoryCost = [];
	public array $rankProbability = [];
	public array $gachaItems = [];

	public function init() : void {
		## Gacha Category
		$this->categorySettingRegister("常駐ガチャ", 0, 1, 60, 33, 5, 1.6, 0.3, 0.1);
		$this->categorySettingRegister("Coming Soon...", 10000000000, 0, 50, 10, 10, 10, 10, 10);
		## 未使用Category用テスト用景品
		$this->dischargeItemRegister("Coming Soon...", RarityMap::C, VanillaBlocks::STONE()->asItem(), 1, "Rank C", "テスト用の景品になります", [], [], []);
		$this->dischargeItemRegister("Coming Soon...", RarityMap::UC, VanillaBlocks::STONE()->asItem(), 1, "Rank UC", "テスト用の景品になります", [], [], []);
		$this->dischargeItemRegister("Coming Soon...", RarityMap::R, VanillaBlocks::STONE()->asItem(), 1, "Rank R", "テスト用の景品になります", [], [], []);
		$this->dischargeItemRegister("Coming Soon...", RarityMap::SR, VanillaBlocks::STONE()->asItem(), 1, "Rank SR", "テスト用の景品になります", [], [], []);
		$this->dischargeItemRegister("Coming Soon...", RarityMap::SSR, VanillaBlocks::STONE()->asItem(), 1, "Rank SSR", "テスト用の景品になります", [], [], []);
		$this->dischargeItemRegister("Coming Soon...", RarityMap::L, VanillaBlocks::STONE()->asItem(), 1, "Rank L", "テスト用の景品になります", [], [], []);
		## C Rank
		$this->dischargeItemRegister("常駐ガチャ", RarityMap::C, VanillaItems::NETHER_QUARTZ(), 1, "はずれ石", "一枚のTicketに変換出来ます\n\n/ticket", [], [], ["ExchangeTicket"]);
		$this->dischargeItemRegister("常駐ガチャ", RarityMap::C, VanillaItems::NETHER_STAR(), 1, "通貨アイテム", "/shop->LevelShop1->通貨アイテム\nから換金することが可能です", [], [], []);
		$this->dischargeItemRegister("常駐ガチャ", RarityMap::C, VanillaItems::STEAK(), 1, "ステーキ", "食料です！！！！！！！！！！！！", [], [], []);
		$this->dischargeItemRegister("常駐ガチャ", RarityMap::C, VanillaItems::PUMPKIN_PIE(), 1, "かぼちゃのパイ", "とっても甘いかぼちゃのパイ", [], [], []);
		$this->dischargeItemRegister("常駐ガチャ", RarityMap::C, VanillaItems::LIGHT_BLUE_DYE(), 1, "水色のごみ", "これが本当のごみ", [], [], []);
		$this->dischargeItemRegister("常駐ガチャ", RarityMap::C, VanillaItems::BONE(), 1, "ただの骨", "成人した人間の骨って206本もあるんだって！", [], [], []);
		$this->dischargeItemRegister("常駐ガチャ", RarityMap::C, VanillaItems::BONE(), 1, "びーぼの骨", "びーぼがいつも握りしめてるお気に入りの骨", [VanillaEnchantments::PUNCH(), VanillaEnchantments::MENDING()], [1, 1], []);
		$this->dischargeItemRegister("常駐ガチャ", RarityMap::C, VanillaItems::NETHER_QUARTZ(), 1, "ぴかぴかした石", "きれいですね", [VanillaEnchantments::UNBREAKING()], [1], []);
		$this->dischargeItemRegister("常駐ガチャ", RarityMap::C, VanillaItems::COOKED_SALMON(), 1, "かき氷", "イチゴ味！", [], [], []);
		## UC Rank
		$this->dischargeItemRegister("常駐ガチャ", RarityMap::UC, VanillaBlocks::FURNACE()->setLit(true)->asItem(), 1, "§c熱を帯びた竈", "大変熱いため叩くと相手が燃える", [VanillaEnchantments::FIRE_ASPECT()], [1], []);
		$this->dischargeItemRegister("常駐ガチャ", RarityMap::UC, VanillaItems::COOKED_MUTTON(), 1, "猫用チュール", "人間用ではない", [], [], []);
		$this->dischargeItemRegister("常駐ガチャ", RarityMap::UC, VanillaItems::COOKED_CHICKEN(), 1, "犬用チュール", "人間用ではない", [], [], []);
		$this->dischargeItemRegister("常駐ガチャ", RarityMap::UC, VanillaItems::COOKED_PORKCHOP(), 1, "アメリカンドック", "アメリカンドッグはソーセージに串を刺し、\n小麦粉などで作った衣をつけて油で揚げた食品。\nアメリカで普及しているコーンミールの生地を使った\nコーン・ドッグを改良したものであり、\n名称は和製英語である。\n\n引用 Wiki", [], [], []);
		$this->dischargeItemRegister("常駐ガチャ", RarityMap::UC, VanillaBlocks::GLASS()->asItem(), 1, "エンジェルブロック", "自分の下にブロックを設置する", [], [], ["AirBlock"]);
		$this->dischargeItemRegister("常駐ガチャ", RarityMap::UC, VanillaItems::EXPERIENCE_BOTTLE(), 16, "魔剤セット", "ちょっとずつ飲もう。。。", [], [], []);
		$this->dischargeItemRegister("常駐ガチャ", RarityMap::UC, VanillaBlocks::TNT()->asItem(), 1, "爆発物", "設置すると爆発します", [], [], ["ExplosionBlock"]);
		$this->dischargeItemRegister("常駐ガチャ", RarityMap::UC, VanillaItems::EGG(), 1, "温泉卵", "温まってるので中身はない", [], [], []);
		$this->dischargeItemRegister("常駐ガチャ", RarityMap::UC, VanillaItems::GLASS_BOTTLE(), 1, "エフェクトクリーナー", "現在付与されているエフェクトを全て削除します", [VanillaEnchantments::PUNCH()], [1], ["EffectCleaner"]);
		$this->dischargeItemRegister("常駐ガチャ", RarityMap::UC, VanillaItems::COOKED_FISH(), 1, "ラムネ", "美味しいよね", [], [], []);
		$this->dischargeItemRegister("常駐ガチャ", RarityMap::UC, VanillaItems::PAPER(), 1, "バスタオル", "常時濡れてるので叩かれると若干痛い", [VanillaEnchantments::SHARPNESS()], [1], []);
		## R Rank
		$this->dischargeItemRegister("常駐ガチャ", RarityMap::R, VanillaItems::MAGMA_CREAM(), 1, "ラッキー苗木キューブ", "何かの苗木がもらえるよ！", [VanillaEnchantments::PUNCH()], [1], ["LuckyTreeCoin"]);
		$this->dischargeItemRegister("常駐ガチャ", RarityMap::R, VanillaItems::COOKED_RABBIT(), 1, "モンスター", "カフェイン摂取キモチエェェェ！", [], [], []);
		$this->dischargeItemRegister("常駐ガチャ", RarityMap::R, VanillaBlocks::COBBLESTONE_STAIRS()->asItem(), 1, "鈍器", "殴られたら痛いじゃ済まないだろう", [VanillaEnchantments::SHARPNESS()], [50], []);
		$this->dischargeItemRegister("常駐ガチャ", RarityMap::R, VanillaItems::STONE_SHOVEL(), 1, "鋭利なシャベル", "ゾンビを倒すために使うものではない", [VanillaEnchantments::SHARPNESS()], [5], []);
		$this->dischargeItemRegister("常駐ガチャ", RarityMap::R, VanillaItems::IRON_PICKAXE(), 1, "溶鉱炉付きのつるはし", "一部のブロックを採掘時に焼いちゃうぞ！うおうお！", [VanillaEnchantments::SILK_TOUCH(), VanillaEnchantments::PUNCH()], [1, 1], ["BlastFurnacePickaxe"]);
		$this->dischargeItemRegister("常駐ガチャ", RarityMap::R, VanillaBlocks::POPPY()->asItem(), 1, "§cﾌｧｲﾔｰﾌﾗﾜｰ", "ふぁいやー！！！", [VanillaEnchantments::FIRE_ASPECT()], [1], []);
		$this->dischargeItemRegister("常駐ガチャ", RarityMap::R, ItemFactory::getInstance()->get(751), 1, "落下ダメージ完全防御ブーツ", "着用していると落下ダメージから自分を守ってくれる", [], [], ["DefensiveStone"]);
		$this->dischargeItemRegister("常駐ガチャ", RarityMap::R, VanillaBlocks::CORAL_FAN()->setCoralType(CoralType::BRAIN())->asItem(), 1, "ライトムーシュルーム", "脳を覚醒させてくれる\n\n効果\n\n暗視 lv.1 60分\n1/5の確率で毒 lv.3 15秒", [VanillaEnchantments::PUNCH()], [1], ["LightMushroom"]);
		$this->dischargeItemRegister("常駐ガチャ", RarityMap::R, VanillaItems::RAW_FISH(), 1, "ZONe", "効果\n\nコンジットパワー lv.3 2分\nウィザー lv.15 30秒\n浮遊 lv.15 30秒", [VanillaEnchantments::PUNCH()], [1], ["ZONe"]);
		$this->dischargeItemRegister("常駐ガチャ", RarityMap::R, VanillaItems::COMPASS(), 1, "プレイヤー追跡機", "同じワールドにいる他プレイヤーの居場所から自身の座標まで直線状のパーティクルを出現させる", [VanillaEnchantments::PUNCH()], [1], ["PlayersGetLocation"]);
		## SR Rank
		$this->dischargeItemRegister("常駐ガチャ", RarityMap::SR, VanillaItems::COOKED_MUTTON(), 1, "ちゅーる", "効果\n\n体力増強 lv.1 30s\nスピード lv.2 30秒", [], [], ["Churu"]);
		$this->dischargeItemRegister("常駐ガチャ", RarityMap::SR, VanillaItems::COOKED_CHICKEN(), 1, "ちゅーる", "効果\n\n体力増強 lv.1 30s\nスピード lv.2 30秒", [], [], ["Churu"]);
		$this->dischargeItemRegister("常駐ガチャ", RarityMap::SR, VanillaBlocks::Lantern()->asItem(), 1, "ヘイストアイテム", "妖精の入ったランタンを照らして。。。\n\n効果\n\n採掘速度上昇 lv.50 30秒\n移動速度低下 lv.3 90秒", [VanillaEnchantments::PUNCH()], [1], ["HasteItem"]);
		$this->dischargeItemRegister("常駐ガチャ", RarityMap::SR, VanillaBlocks::CORAL_FAN()->setCoralType(CoralType::FIRE())->asItem(), 1, "天国草", "天国で群生してる草\n\n効果\n\n移動速度低下 lv.5 5秒\n耐性 lv.3 2分\n25秒後に吐き気 lv.15 15秒", [VanillaEnchantments::PUNCH()], [1], ["HeavenGrass"]);
		$this->dischargeItemRegister("常駐ガチャ", RarityMap::SR, VanillaItems::WATER_POTION(), 1, "RedBull", "翼を授ける\n\n効果\n\n浮遊 lv.30 3秒", [VanillaEnchantments::PUNCH()], [1], ["RedBull"]);
		$this->dischargeItemRegister("常駐ガチャ", RarityMap::SR, VanillaItems::WOODEN_PICKAXE(), 1, "すぴにー(実写)", "効果\n\nなし", [VanillaEnchantments::PUNCH()], [1], []);
		$this->dischargeItemRegister("常駐ガチャ", RarityMap::SR, VanillaItems::ENDER_PEARL(), 1, "ラッキーEXPオーブ", "expがもらえるよ！", [VanillaEnchantments::PUNCH()], [1], ["LuckyExpCoin"]);
		$this->dischargeItemRegister("常駐ガチャ", RarityMap::SR, VanillaItems::GOLD_NUGGET(), 1, "ラッキーマネーコイン", "お金がもらえるよ！", [VanillaEnchantments::PUNCH()], [1], ["LuckyMoneyCoin"]);
		$this->dischargeItemRegister("常駐ガチャ", RarityMap::SR, VanillaBlocks::MONSTER_SPAWNER()->asItem(), 1, "レベルブロック", "れぇべぇるぅ\n破壊するとバニラ経験値がもらえます", [], [], []);
		$this->dischargeItemRegister("常駐ガチャ", RarityMap::SR, VanillaItems::DIAMOND_PICKAXE(), 1, "黒曜石ブレイカー", "黒曜石だけを徹底的に破壊する", [VanillaEnchantments::PUNCH()], [1], ["ObsidianBreaker"]);
		$this->dischargeItemRegister("常駐ガチャ", RarityMap::SR, VanillaItems::DIAMOND_HOE(), 1, "グロウストーンブレイカー", "グロウストーンだけを徹底的に破壊する", [VanillaEnchantments::PUNCH()], [1], ["GlowstoneBreaker"]);
		## SSR Rank
		$this->dischargeItemRegister("常駐ガチャ", RarityMap::SSR, VanillaItems::DRAGON_BREATH(), 1, "繝｢繝ｳ繧ｹ繧ｿ繝ｼ", "驛｢譎｢?ｽ?｢驛｢譎｢?ｽ?ｳ驛｢?ｧ??ｹ驛｢?ｧ??ｿ驛｢譎｢?ｽ?ｼ", [VanillaEnchantments::PUNCH()], [1], ["UnknownItem"]);
		$this->dischargeItemRegister("常駐ガチャ", RarityMap::SSR, VanillaItems::SWEET_BERRIES(), 1, "コーラル バタフライ", "効果\n\n採掘速度上昇 lv.50 15分\n二分の一の確率でデメリットが発生します", [VanillaEnchantments::PUNCH()], [1], ["CoralButterfly"]);
		$this->dischargeItemRegister("常駐ガチャ", RarityMap::SSR, VanillaItems::POISONOUS_POTATO(), 1, "クリーミーポテト", "本当にクリームみたいな柔らかさのじゃかいもだね\n\n効果\n\n採掘速度上昇 lv.3 60秒\n5秒経過後に毒 lv.5 25秒", [VanillaEnchantments::PUNCH()], [1], ["CreamyPotato"]);
		$this->dischargeItemRegister("常駐ガチャ", RarityMap::SSR, VanillaItems::FISHING_ROD(), 1, "SuperRod", "どこまでも走れる気がする", [VanillaEnchantments::PUNCH()], [1], []);
		$this->dischargeItemRegister("常駐ガチャ", RarityMap::SSR, VanillaItems::GOLD_INGOT(), 1, "道具名チェンジャー", "道具、装備の名前を変更することが可能です\n名前を変更したいアイテムを持った状態で/itemsコマンドを実行してください", [VanillaEnchantments::PROJECTILE_PROTECTION()], [5], ["ItemNameChangeIngot"]);
		$this->dischargeItemRegister("常駐ガチャ", RarityMap::SSR, VanillaItems::IRON_INGOT(), 1, "修繕費用代替インゴット", "アイテムの修繕費用(ExpLevel)を代替します", [VanillaEnchantments::MENDING()], [1], ["repairIngot"]);
		$this->dischargeItemRegister("常駐ガチャ", RarityMap::SSR, VanillaItems::DIAMOND_SHOVEL(), 1, "§a[Gacha]DiamondMiningShovel", "がちゃ仕様の特殊範囲破壊シャベル\n\n自由にエンチャントしたり修繕することが可能です", [], [], ["MiningTools_3", "gacha_mining"]);
		$this->dischargeItemRegister("常駐ガチャ", RarityMap::SSR, VanillaItems::DIAMOND_PICKAXE(), 1, "§a[Gacha]DiamondMiningPickaxe", "がちゃ仕様の特殊範囲破壊ピッケル\n\n自由にエンチャントしたり修繕することが可能です", [], [], ["MiningTools_3", "gacha_mining"]);
		$this->dischargeItemRegister("常駐ガチャ", RarityMap::SSR, VanillaItems::DIAMOND_AXE(), 1, "§a[Gacha]DiamondMiningAxe", "がちゃ仕様の特殊範囲破壊アックス\n\n自由にエンチャントしたり修繕することが可能です", [], [], ["MiningTools_3", "gacha_mining"]);
		## L Rank
		$this->dischargeItemRegister("常駐ガチャ", RarityMap::L, ItemFactory::getInstance()->get(-302), 1, "MiningToolRangeExpansion", "Netherite MiningToolsの範囲を強化できます/mt", [], [], ["MiningToolsRangeCostItem"]);
		$this->dischargeItemRegister("常駐ガチャ", RarityMap::L, ItemFactory::getInstance()->get(174), 1, "MiningToolEnchantExpansion", "Netherite MiningToolsのエンチャントを強化できます/mt", [], [], ["MiningToolsEnchantCostItem"]);
		$this->dischargeItemRegister("常駐ガチャ", RarityMap::L, VanillaBlocks::LAPIS_LAZULI()->asItem(), 1, "EnablingMiningSettingExpansion", "MiningToolsの設定機能を強化できます/mt", [], [], ["EnablingMiningSettingItem"]);
		$this->dischargeItemRegister("常駐ガチャ", RarityMap::L, VanillaItems::BOOK(), 1, "修繕エンチャントブック", "/itemsから道具と装備に修繕エンチャントを付与することが可能です\nまた、MiningToolsには付与出来ませんのでご注意ください\n\nMiningToolsに付与する場合はネザライトMiningToolsの耐久項目を最大強化する必要があります", [VanillaEnchantments::MENDING()], [1], ["AddMendingEnchantmentItem"]);
	}

	private function categorySettingRegister(string $category, int $moneyCost, int $ticketCost, float $C, float $UC, float $R, float $SR, float $SSR, float $L) : void {
		$this->categoryCost[$category][] = ["moneyCost" => $moneyCost, "ticketCost" => $ticketCost];
		$this->rankProbability[$category][] = ["C" => $C, "UC" => $UC, "R" => $R, "SR" => $SR, "SSR" => $SSR, "L" => $L];
	}

	private function dischargeItemRegister(string $category, string $rank, Item $item, int $count, string $name, string $lore, array $enchants, array $level, array $nbt) : void {
		$this->checkFunctionArgument($category, $rank, $enchants, $level);
		$this->gachaItems[$category][$rank][] = ["item" => $item, "count" => $count, "name" => $name, "lore" => $lore, "enchants" => $enchants, "level" => $level, "nbt" => $nbt];
	}

	private function checkFunctionArgument(string $category, string $rank, array $enchants, array $level) : void {
		if (!in_array($rank, RarityMap::AllRarity, true)) {
			throw new Error("Gacha : アイテム登録時にRarityMapに登録されていないRarityが入力された為プラグインを停止します");
		}
		if (!in_array($category, self::Category, true)) {
			throw new Error("Gacha : アイテム登録時にカテゴリーに登録されていないガチャが入力された為プラグインを停止します");
		}
		if (count($enchants) !== count($level)) {
			throw new Error("Gacha : アイテム登録時にエンチャントとレベルの数が一致していない為プラグインを停止します");
		}
	}

	public static function getInstance() : GachaItemAPI {
		if (!isset(self::$instance)) {
			self::$instance = new GachaItemAPI();
		}
		return self::$instance;
	}
}
