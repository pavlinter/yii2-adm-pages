<?php

namespace pavlinter\admpages;

/**
 * Class PageAsset
 */
class PageAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@vendor/pavlinter/yii2-adm-pages/admpages/assets';
    public $css = [

    ];
    public $js = [
        'js/common.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset'
    ];
}