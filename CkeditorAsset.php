<?php
namespace kuakling\keditor;

use yii\web\AssetBundle;

/**
 * Asset bundle for the Materialize css files.
 *
 * @author kuakling <kuakling@gmail.com>
 * @since 2.0
 */
class MaterializeAsset extends AssetBundle
{
    public $sourcePath = '@bower/ckeditor';
    public $css = [
        'css/materialize.min.css',
    ];
    public $js = [
      'ckeditor.js',
    ];
}