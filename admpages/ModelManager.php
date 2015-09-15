<?php

/**
 * @package yii2-adm-pages
 * @author Pavels Radajevs <pavlinter@gmail.com>
 * @copyright Copyright &copy; Pavels Radajevs <pavlinter@gmail.com>, 2015
 * @version 1.0.4
 */

namespace pavlinter\admpages;

use pavlinter\adm\Manager;
use Yii;

/**
 * @method \pavlinter\admpages\models\Page staticPage
 * @method \pavlinter\admpages\models\Page createPage
 * @method \pavlinter\admpages\models\Page createPageQuery
 * @method \pavlinter\admpages\models\PageSearch createPageSearch
 * @method \pavlinter\admpages\models\PageLang staticPageLang
 * @method \pavlinter\admpages\models\PageLang createPageLang
 * @method \pavlinter\admpages\models\PageLang createPageLangQuery
 */
class ModelManager extends Manager
{
    /**
     * @var string|\pavlinter\admpages\models\Page
     */
    public $pageClass = 'pavlinter\admpages\models\Page';
    /**
     * @var string|\pavlinter\admpages\models\PageSearch
     */
    public $pageSearchClass = 'pavlinter\admpages\models\PageSearch';
    /**
     * @var string|\pavlinter\admpages\models\PageLang
     */
    public $pageLangClass = 'pavlinter\admpages\models\PageLang';
}