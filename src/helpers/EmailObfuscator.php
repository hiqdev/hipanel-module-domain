<?php
/**
 * Domain plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-domain
 * @package   hipanel-module-domain
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\modules\domain\helpers;

use Yii;

class EmailObfuscator
{
    /**
     * default font size.
     *
     * @var int
     */
    private $size = 11;

    /**
     * rotation in degrees.
     *
     * @var int
     */
    private $rot = 0;

    /**
     * horizontal padding.
     *
     * @var int
     */
    private $hpad = 0;

    /**
     * vertical padding.
     *
     * @var int
     */
    private $vpad = 0;

    /**
     * transparency.
     *
     * @var boolean
     */
    private $transparent = true;

    /**
     * foreground red.
     *
     * @var int
     */
    private $red = 0;

    /**
     * foreground green.
     *
     * @var int
     */
    private $grn = 0;

    /**
     * foreground blue.
     *
     * @var int
     */
    private $blu = 0;

    /**
     * background red.
     *
     * @var int
     */
    private $bg_red = 255;

    /**
     * background green.
     *
     * @var int
     */
    private $bg_grn = 255;

    /**
     * background blue.
     *
     * @var int
     */
    private $bg_blu = 255;

    /**
     * @var string the TrueType font file. This can be either a file path or path alias.
     */
    public $fontFile = '@yii/captcha/SpicyRice.ttf';

    public function generatePng($text)
    {
        $font = Yii::getAlias($this->fontFile);

        // get the font height.
        $bounds = imagettfbbox($this->size, $this->rot, $font, 'W');
        if ($this->rot < 0) {
            $font_height = abs($bounds[7] - $bounds[1]);
        } elseif ($this->rot > 0) {
            $font_height = abs($bounds[1] - $bounds[7]);
        } else {
            $font_height = abs($bounds[7] - $bounds[1]);
        }
        // determine bounding box.
        $bounds = imagettfbbox($this->size, $this->rot, $font, $text);
        if ($this->rot < 0) {
            $width = abs($bounds[4] - $bounds[0]);
            $height = abs($bounds[3] - $bounds[7]);
            $offset_y = $font_height;
            $offset_x = 0;
        } elseif ($this->rot > 0) {
            $width = abs($bounds[2] - $bounds[6]);
            $height = abs($bounds[1] - $bounds[5]);
            $offset_y = abs($bounds[7] - $bounds[5]) + $font_height;
            $offset_x = abs($bounds[0] - $bounds[6]);
        } else {
            $width = abs($bounds[4] - $bounds[6]);
            $height = abs($bounds[7] - $bounds[1]);
            $offset_y = $font_height;
            $offset_x = 0;
        }

        $image = imagecreate($width + ($this->hpad * 2) + 1, $height + ($this->vpad * 2) + 1);
        $background = imagecolorallocate($image, $this->bg_red, $this->bg_grn, $this->bg_blu);
        $foreground = imagecolorallocate($image, $this->red, $this->grn, $this->blu);

        if ($this->transparent) {
            imagecolortransparent($image, $background);
        }
        imageinterlace($image, false);

        imagettftext($image, $this->size, $this->rot, $offset_x + $this->hpad, $offset_y + $this->vpad, $foreground, $font, $text);

        ob_start();
        ob_implicit_flush(false);
        imagepng($image);

        return ob_get_clean();
    }
}
