<?php
namespace kuakling\keditor;

use yii\web\AssetBundle;

/**
 * Asset bundle for the Materialize css files.
 *
 * @author kuakling <kuakling@gmail.com>
 * @since 2.0
 */
class TinyMceAsset extends AssetBundle
{
    public $sourcePath = '@vendor/tinymce/tinymce';
    public $css = [
    ];
    public $js = [
      'tinymce.min.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset'
    ];
}
