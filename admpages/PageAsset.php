<?php

/**
 * @package yii2-adm-pages
 * @author Pavels Radajevs <pavlinter@gmail.com>
 * @copyright Copyright &copy; Pavels Radajevs <pavlinter@gmail.com>, 2015
 * @version 1.0.2
 */

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