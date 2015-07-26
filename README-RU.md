yii2-human-formatter
=================================

Это форматер для yii2 который переопрделяет базовый форматер. Переопрделена метод вывода 
даты и времени и добавлено метод форматирование телефонов (пока только Российские номера)

[english readme](https://github.com/microinginer/yii2-human-formatter/blob/master/README.md)

Пример работы
---------------
Использование форматера в виде метода
```php
echo Yii::$app->formatter->asDatetime(time()); // сегодня в 23:55
echo Yii::$app->formatter->asDatetime(strtotime('- 1 day')); // вчера в 23:55
echo Yii::$app->formatter->asDatetime('2014-08-23 23:55:12'); //  23 августа 2014 в 23:55
```

Задание формата аттрибуту
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
Установка
------------

Предпочтительный способ установки [composer](http://getcomposer.org/download/).

Запустите комманду

```
php composer.phar require --prefer-dist microinginer/yii2-human-formatter "dev-master"
```

или добавьте

```json
"microinginer/yii2-human-formatter": "dev-master"
```

в раздел `require` в вашем composer.json файле.

В конфигурационном файле добавляем:
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


