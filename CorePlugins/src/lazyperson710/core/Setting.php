<?php

namespace lazyperson710\core;

class Setting {

    private static array $coordinate;           //座標の表示
    private static array $joinItems;            //ログイン時のアイテム配布
    private static array $directDropItemStorage;//Inventoryに空きがあってもストレージに
    private static array $levelUpTitle;         //レベルアップ時のタイトル
    private static array $enduranceWarning;     //耐久値の警告
    private static array $destructionSound;     //ブロック破壊時のExp音
    //private static array $chatMute;要らんかも
}