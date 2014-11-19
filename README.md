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
                'pageTypes' => [
                   'page' => 'Page',
                   'news' => 'News',
                ],
                'pageLayout' => '/main',
                'closeDeletePage' => [] //id [2,130]
            ],
            ...
        ],
        ...
    ],
    ...
],
```

Запустить миграцию
-------------
```php
yii migrate --migrationPath=@vendor/pavlinter/yii2-adm-pages/admpages/migrations
```