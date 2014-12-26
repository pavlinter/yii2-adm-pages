Yii2: Adm-Pages Модуль для Adm CMS
================

Установка
------------
Удобнее всего установить это расширение через [composer](http://getcomposer.org/download/).

```
   "pavlinter/yii2-adm-pages": "*",
```

Настройка
-------------
```php
'modules' => [
    ...
    'adm' => [
        ...
        'modules' => [
            'admpages' => [
                'class' => 'pavlinter\admpages\Module',
                'pageLayouts' => [
                   'page' => 'Page',
                   'page-image' => 'Page + image',
                ],
                'pageTypes' => [],
                'pageLayout' => '/main',
                'closeDeletePage' => [] //id [2,130]
            ],
            ...
        ],
        ...
    ],
    ...
],
'components' => [
    ...
    'urlManager' => [
        ....
        'rules'=>[
            '' => 'site/main-page',
            'page/<alias:([A-Za-z0-9_-])+>' => 'site/page',
        ],
    ],
    ...
],
```

```php
//SiteController
class SiteController extends Controller
{
    ...
    public function actions()
    {
        return [
            'page' => [
                'class' => 'pavlinter\admpages\actions\PageAction',
            ],
            'main-page' => [
                'class' => 'pavlinter\admpages\actions\PageAction',
                'isMainPage' => true,
            ],
        ];
    }
    ...
}
```

Запустить миграцию
-------------
```php
yii migrate --migrationPath=@vendor/pavlinter/yii2-adm-pages/admpages/migrations
```

Как использовать
-------------
```php
echo Html::a('My-Page',['site/page', 'alias' => 'My-Page']);
```