yii2-human-formatter
=================================

This formatter for yii2 which extend basic formatter. Override
datetime and add method of formatting phones (only Russia's number)

[russian readme](https://github.com/microinginer/yii2-human-formatter/blob/master/README.md)

Example
---------------

```php
echo Yii::$app->formatter->asDatetime(time()); // 0 seconds ago
echo Yii::$app->formatter->asDatetime(strtotime('- 1 day')); // Yesterday at 11:55 pm
echo Yii::$app->formatter->asDatetime('2014-08-23 23:55:12'); //  August 23 at 11:55 pm
```

```php
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'id',
        'title',
        'mobile:phone',
        'created_at:datetime',
        'description:ntext',
    ],
]);
```

Install
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist microinginer/yii2-human-formatter "dev-master"
```

or add

```json
"microinginer/yii2-human-formatter": "dev-master"
```
to the require section of your composer.json file.

add to config:
```php
$config = [
    ...
    'components' => [
        ...
        'formatter'  => [
            'class' => 'microinginer\humanFormatter\HumanFormatter',
        ]
        ...
    ]
    ...
]
```


