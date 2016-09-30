<?php

namespace hipanel\modules\domain\widgets;

use hipanel\modules\domain\helpers\EmailObfuscator;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\captcha\Captcha;
use yii\helpers\Html;

class WhoisData extends Widget
{
    public $data;

    public function init()
    {
        if (!$this->data) {
            throw new InvalidConfigException('Parameter "$data" is required');
        }
        $this->data = implode("\n", $this->data);

        $this->mailToImage();
    }

    public function run()
    {
        return nl2br(trim($this->data));
    }

    private function mailToImage()
    {
        preg_match_all('/[a-z0-9_.+-]+@(?:[a-z0-9_-]+\.)+[a-z]{2,4}/i', $this->data, $matches);
        if (!empty($matches[0])) {
            foreach ($matches[0] as $mail) {
                $tag = Html::img('data:image/png;base64,' . base64_encode($this->stringToPng($mail)));
                $this->data = str_replace($mail, $tag, $this->data);
            }
        }
    }

    private function stringToPng($string)
    {
        return (new EmailObfuscator())->generatePng($string);
    }
}
