<?php

namespace microinginer\humandatetime;


use Yii;
use yii\base\InvalidConfigException;
use yii\base\InvalidParamException;
use yii\i18n\Formatter;

/**
 * Class HumanDatetime
 *
 * @author Ruslan Madatov <ruslanmadatov@yandex.ru>
 * @author Mihail Gerasimov <uzep@mail.ru>
 */
class HumanDatetime extends Formatter
{
    /**
     * @var array
     */
    private static $month = [
        'января',
        'февраля',
        'марта',
        'апреля',
        'мая',
        'июня',
        'июля',
        'августа',
        'сентября',
        'октября',
        'ноября',
        'декабря',
    ];

    /**
     * @var array
     */
    private static $periods = [
        'second',
        'minute',
        'hour',
        'day',
        'week',
        'month',
        'years',
    ];
    /**
     * @var array
     */
    private static $lengths = [
        '60',
        '60',
        '24',
        '7',
        '4.35',
        '12',
    ];

    /**
     * Formats the value as a datetime.
     * @param integer|string|DateTime $value the value to be formatted. The following
     * types of value are supported:
     *
     * - an integer representing a UNIX timestamp
     * - a string that can be [parsed to create a DateTime object](http://php.net/manual/en/datetime.formats.php).
     *   The timestamp is assumed to be in [[defaultTimeZone]] unless a time zone is explicitly given.
     * - a PHP [DateTime](http://php.net/manual/en/class.datetime.php) object
     *
     * @param string $format the format used to convert the value into a date string.
     * If null, [[dateFormat]] will be used.
     *
     * This can be 'short', 'medium', 'long', or 'full', which represents a preset format of different lengths.
     * It can also be a custom format as specified in the [ICU manual](http://userguide.icu-project.org/formatparse/datetime).
     *
     * Alternatively this can be a string prefixed with `php:` representing a format that can be recognized by the
     * PHP [date()](http://php.net/manual/de/function.date.php)-function.
     *
     * @return string the formatted result.
     * @throws InvalidParamException if the input value can not be evaluated as a date value.
     * @throws InvalidConfigException if the date format is invalid.
     * @see datetimeFormat
     */
    public function asDatetime ($value, $format = null)
    {
        switch ($this->locale) {
            case 'ru': {
                return $this->datetimeAsHumanRu($value, $format);
            }
            case 'en': {
                return $this->datetimeAsHumanEn($value, $format);
            }
            default: {
                return parent::asDatetime($value, $format);
            }
        }
    }

    /**
     * @param $date
     * @param $format
     * @return string
     */
    private function datetimeAsHumanRu ($date, $format)
    {
        $result = '';

        if (!is_numeric($date)) {
            $date = strtotime($date);
        }

        switch ($format) {
            case 'human':
            default: {
                if (date('dmy', $date) == date('dmy')) {
                    $result .= 'сегодня';
                } elseif (date('dmy', $date) == date('dmy', mktime(0, 0, 0, date('m'), date('d') + 1, date('y')))) {
                    $result .= 'завтра';
                } elseif (date('dmy', $date) == date('dmy', mktime(0, 0, 0, date('m'), date('d') - 1, date('y')))) {
                    $result .= 'вчера';
                } elseif (date('dmy', $date) == date('dmy', mktime(0, 0, 0, date('m'), date('d') + 2, date('y')))) {
                    $result .= 'послезавтра';
                } else {
                    if (date('y', $date) == date('y')) {
                        $result .= date('d', $date) . ' ' . self::$month[ date('n', $date) - 1 ];
                    } else {
                        $result .= date('d', $date) . ' ' . self::$month[ date('n', $date) - 1 ] . ' ' . date('Y', $date);
                    }
                }
                $result .= ' в ' . date('H:i', $date);
                break;
            }
        }

        return $result;
    }

    /**
     * @param $date
     * @param $format
     * @return string
     */
    public function datetimeAsHumanEn ($date, $format)
    {
        if (!is_numeric($date)) {
            $date = strtotime($date);
        }

        $difference = time() - $date;

        if ($difference >= 0) {
            $ending = 'ago';
        } else {
            $difference = -$difference;
            $ending = 'to go';
        }
        $arr_len = count(self::$lengths);
        for ($j = 0; $j < $arr_len && $difference >= self::$lengths[ $j ]; $j++) {
            $difference /= self::$lengths[ $j ];
        }

        $difference = round($difference);
        if ($difference != 1) {
            self::$periods[ $j ] .= 's';
        }
        $result = $difference . ' ' . self::$periods[ $j ] . ' ' . $ending;

        switch ($format) {
            case 'human':
            default: {

                if ($j > 2) {
                    if ($ending == 'to go') {
                        if ($j == 3 && $difference == 1) {
                            $result = 'Tomorrow at ' . date('g:i a', $date);
                        } else {
                            $result = date('F j, Y \a\t g:i a', $date);
                        }

                        return $result;
                    }
                    if ($j == 3 && $difference == 1) {
                        $result = 'Yesterday at ' . date('g:i a', $date);
                    } else if ($j == 3) {
                        $result = date('l \a\t g:i a', $date);
                    } else if ($j < 6 && !($j == 5 && $difference == 12)) {
                        $result = date('F j \a\t g:i a', $date);
                    } else {
                        $result = date('F j, Y \a\t g:i a', $date);
                    }
                }

                return $result;
            }
        }
    }
}