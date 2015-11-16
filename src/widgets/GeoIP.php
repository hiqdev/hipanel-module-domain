<?php

/*
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2015, HiQDev (http://hiqdev.com/)
 */

/**
 * @link    http://hiqdev.com/hipanel-module-domain
 *
 * @license http://hiqdev.com/hipanel-module-domain/license
 * @copyright Copyright (c) 2015 HiQDev
 */
namespace hipanel\modules\domain\widgets;

use dosamigos\google\maps\LatLng;
use dosamigos\google\maps\Map;
use Yii;
use yii\base\Widget;
use yii\helpers\Html;

class GeoIP extends Widget
{
    /** {@inheritdoc} */
    public $iprecord = [];

    /** @var $header[] */
    public $header = [
        'tag'   => 'div',
        'class' => 'info',
        'title' => 'GeoIP Info',
    ];

    /** @var $mapwidth, string or integer */
    public $mapwidth = '100%';

    /** @var $mapheight, string or integer */
    public $mapheight = 200;

    /** @var integer, between 1..14 */
    public $mapzoom = 9;

    /** @var bool */
    public $map = true;

    /** @var string - ip */
    public $ip;

    public static function widget($config = [])
    {
        return parent::widget($config);
    }

    public function run()
    {
        parent::run();
        if (function_exists('geoip_record_by_name')) {
            if ($this->iprecord = @geoip_record_by_name($this->ip)) {
                $this->renderHeader();
                if ($this->map) {
                    $this->renderMap();
                }
                $this->renderInfo();
            }
        }
    }

    private function renderHeader()
    {
        print Html::tag($this->header['tag'], $this->header['title'], ['class' => 'label label-' . $this->header['class']]);
    }

    private function renderMap()
    {
        print(new Map([
            'center'    => new LatLng([
                'lat'       => $this->iprecord['latitude'],
                'lng'       => $this->iprecord['longitude'],
            ]),
            'zoom'  => $this->mapzoom,
            'width' => $this->mapwidth,
            'height' => $this->mapheight,
        ]))->display();
    }

    private function renderInfo()
    {
        print Html::tag('p', 'IP: ' . Html::tag('span', $this->ip, ['class' => 'whois-ip']));
        print Html::tag('p', Yii::t('app', 'Country') . ': ' . Html::tag('span', $this->iprecord['country_name'], ['class' => 'whois-ip']));
        print Html::tag('p', Yii::t('app', 'City') . ': ' . Html::tag('span', $this->iprecord['city'], ['class' => 'whois-ip']));
    }
}
