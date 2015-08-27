<?php
namespace kuakling\keditor;

use yii\web\AssetBundle;

/**
 * Asset bundle for the Materialize css files.
 *
 * @author kuakling <kuakling@gmail.com>
 * @since 2.0
 */
class CKEditorAsset extends AssetBundle
{
    public $sourcePath = '@vendor/ckeditor/ckeditor';
    public $css = [
    ];
    public $js = [
      'ckeditor.js',
    ];
    public $depends = ['yii\web\JqueryAsset'];
}
