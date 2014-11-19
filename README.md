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
            '' => 'adm/admpages/default/main', //OR $config['defaultRoute'] = 'adm/admpages/default/main';
            'page/<alias:([A-Za-z0-9_-])+>' => 'adm/admpages/default/index',
        ],
    ],
    ...
],
```

Запустить миграцию
-------------
```php
yii migrate --migrationPath=@vendor/pavlinter/yii2-adm-pages/admpages/migrations
```

Как использовать
-------------
```php
echo Html::a('My-Page',['adm/admpages/default/index', 'alias' => 'My-Page']);
```