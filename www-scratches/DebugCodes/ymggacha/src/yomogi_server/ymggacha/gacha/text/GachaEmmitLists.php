<?php

declare(strict_types = 1);
namespace ymggacha\src\yomogi_server\ymggacha\gacha\text;

use ymggacha\src\yomogi_server\ymggacha\gacha\RarityMap;

class GachaEmmitLists {

    public static function getYmgGachaEmmitLists(): string {
        $ur = RarityMap::NAME_UR;
        $ssr = RarityMap::NAME_SSR;
        $sr = RarityMap::NAME_SR;
        $r = RarityMap::NAME_R;
        $n = RarityMap::NAME_N;
        $per = '%';
        return <<<EMMITLIST
			[よもぎガチャ排出確率一覧表]

			[$ur] 排出確率: 2$per
			・上位エンチャント ダイヤツール
			
			[$ssr] 排出確率: 8$per
			・上位エンチャント 鉄ツール
			・下位エンチャント ダイヤツール
			・上質な粉
			
			[$sr] 排出確率: 10$per
			・上位エンチャント 石ツール
			・下位エンチャント 鉄ツール
			
			[$r] 排出確率: 30$per
			・下位エンチャント 石ツール
			・サンゴブロック各種 (死んだサンゴブロックは含まれません)
			・ネザーレンガブロック
			・暗海晶ブロック
			・レッドストーンランプブロック (on/off)
			・レール
			・苔の生えた丸石ブロック
			・苔の生えた石レンガブロック
			・勤勉の粉
			
			[$n] 排出確率: 50$per
			・にんじん
			・じゃがいも
			・ビートルート
			・スイートベリー
			・骨粉
			EMMITLIST;
    }
}
