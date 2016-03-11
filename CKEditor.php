<?php

namespace kuakling\keditor;

use Yii;
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

    public $uploadDir = '';

    public $uploadURL = '';

    public function init()
    {
        parent::init();
        $this->initOptions();

        if(empty($this->uploadDir)){
            //$this->uploadDir = Yii::$app->project->basePath.DIRECTORY_SEPARATOR.'UserFiles';
            $this->uploadDir = Yii::getAlias('@webroot/UserFiles');
        }
        if(empty($this->uploadURL)){
            $this->uploadURL = Yii::getAlias('@web/UserFiles');
        }
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
                'uploadDir' => $this->uploadDir,
                'uploadURL' => $this->uploadURL,
            ];
            Yii::$app->session->set('KCFINDER', $kcfOptions);

            $htSource = __DIR__.'/upload.htaccess';
            $htDest = $kcfinder->basePath.'/conf/upload.htaccess';
            copy($htSource, $htDest);
        }

        $options = $this->clientOptions !== false && !empty($this->clientOptions)
            ? Json::encode($this->clientOptions)
            : '{}';
        $js[] = "CKEDITOR.replace('$id', $options);";
        $view->registerJs(implode("\n", $js));
    }
}
