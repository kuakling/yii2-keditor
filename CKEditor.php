<?php

namespace kuakling\keditor;

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\widgets\InputWidget;
class CKEditor extends InputWidget
{
    use CKEditorTrait;
    /**
     * @inheritdoc
     */
    public $filemanager = false;
    public function init()
    {
        parent::init();
        $this->initOptions();
    }
    /**
     * @inheritdoc
     */
    public function run()
    {
        if ($this->hasModel()) {
            echo Html::activeTextarea($this->model, $this->attribute, $this->options);
        } else {
            echo Html::textarea($this->name, $this->value, $this->options);
        }
        $this->registerPlugin();
    }
    /**
     * Registers CKEditor plugin
     * @codeCoverageIgnore
     */
    protected function registerPlugin()
    {
        $js = [];
        $view = $this->getView();
        CKEditorAsset::register($view);
        $id = $this->options['id'];

        if ($this->filemanager) {
            $kcfinder = KCFinderAsset::register($view);
            $browse = [
                'filebrowserBrowseUrl' => $kcfinder->baseUrl.'/browse.php?opener=ckeditor&type=files',
                'filebrowserImageBrowseUrl' => $kcfinder->baseUrl.'/browse.php?opener=ckeditor&type=images',
                'filebrowserFlashBrowseUrl' => $kcfinder->baseUrl.'/browse.php?opener=ckeditor&type=flash',
                'filebrowserUploadUrl' => $kcfinder->baseUrl.'/upload.php?opener=ckeditor&type=files',
                'filebrowserImageUploadUrl' => $kcfinder->baseUrl.'/upload.php?opener=ckeditor&type=images',
                'filebrowserFlashUploadUrl' => $kcfinder->baseUrl.'/upload.php?opener=ckeditor&type=flash',
            ];
            $this->clientOptions = ArrayHelper::merge($this->clientOptions, $browse);
            $kcfOptions = [
                'disabled' => false,
                'uploadURL' => \Yii::getAlias('@web').'/upload',
                'access' => [
                    'files' => [
                        'upload' => true,
                        'delete' => false,
                        'copy' => false,
                        'move' => false,
                        'rename' => false,
                    ],
                    'dirs' => [
                        'create' => true,
                        'delete' => false,
                        'rename' => false,
                    ],
                ],
            ];
            \Yii::$app->session->set('KCFINDER', $kcfOptions);
        }

        $options = $this->clientOptions !== false && !empty($this->clientOptions)
            ? Json::encode($this->clientOptions)
            : '{}';
        $js[] = "CKEDITOR.replace('$id', $options);";
        $view->registerJs(implode("\n", $js));
    }
}
