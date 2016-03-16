<?php
namespace kuakling\keditor;

use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\InputWidget;
/**
 *
 * TinyMCE renders a tinyMCE js plugin for WYSIWYG editing.
 *
 */
class TinyMce extends InputWidget
{
    public $language;

    public $clientOptions = [];

    public $triggerSaveOnBeforeValidateForm = true;

    public $enableFilemanager = false;

    public $folderName = ['file' => 'file', 'image'=>'image', 'media' => 'media'];


    protected $uploadDir = '';

    protected $uploadURL = '';

    public function init()
    {
        parent::init();

        if(empty($this->uploadDir)){
            $this->uploadDir = Yii::getAlias('@webroot/UserFiles');
        }
        if(empty($this->uploadURL)){
            $this->uploadURL = Yii::getAlias('@web/UserFiles');
        }
    }
    
    public function run()
    {
        if ($this->hasModel()) {
            echo Html::activeTextarea($this->model, $this->attribute, $this->options);
        } else {
            echo Html::textarea($this->name, $this->value, $this->options);
        }
        $this->registerClientScript();
    }
    /**
     * Registers tinyMCE js plugin
     */
    protected function registerClientScript()
    {
        $js = [];
        $view = $this->getView();
        TinyMceAsset::register($view);
        $id = $this->options['id'];
        $this->clientOptions = [
            'menubar' => !Yii::$app->user->isGuest,
            'statusbar' => !Yii::$app->user->isGuest,
            'plugins' => [
                "advlist autolink link image lists charmap print preview hr anchor pagebreak",
                "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
                "table contextmenu directionality emoticons paste textcolor code"
            ],
            'toolbar1' => "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
            'toolbar2' => "| link unlink anchor | image media | forecolor backcolor ",
            'height' => '500px',
            'relative_urls' => false,
        ];
        $this->clientOptions['selector'] = "#$id";
        
        // @codeCoverageIgnoreStart
        /*if ($this->language !== null) {
            $langFile = "langs/{$this->language}.js";
            $langAssetBundle = TinyMceLangAsset::register($view);
            $langAssetBundle->js[] = $langFile;
            $this->clientOptions['language_url'] = $langAssetBundle->baseUrl . "/{$langFile}";
        }*/
        // @codeCoverageIgnoreEnd

        if($this->enableFilemanager && !Yii::$app->user->isGuest){
            $kcfinder = KCFinderAsset::register($view);
            $this->clientOptions['file_browser_callback'] = new \yii\web\JsExpression("
            function(field, url, type, win) {
                var fileType = ".Json::encode($this->folderName).";
                tinyMCE.activeEditor.windowManager.open({
                    file: '".$kcfinder->baseUrl."/browse.php?opener=tinymce4&field=' + field + '&type=' + fileType[type],
                    title: 'File manager',
                    width: 700,
                    height: 500,
                    inline: true,
                    close_previous: false
                }, {
                    window: win,
                    input: field
                });
                return false;
            }"
            );

            $kcfOptions = [
                'disabled' => false,
                'uploadDir' => $this->uploadDir,
                'uploadURL' => $this->uploadURL,
                'types' => array(
                // (F)CKEditor types
                    'files'   =>  "",
                    'flash'   =>  "swf",
                    'images'  =>  "*img",

                // TinyMCE types
                    'file'    =>  "",
                    'media'   =>  "swf flv avi mpg mpeg qt mov wmv asf rm",
                    'image'   =>  "*img",

                //Other
                    'File'    =>  "",
                    'Media'   =>  "swf flv avi mpg mpeg qt mov wmv asf rm",
                    'Image'   =>  "*img",
                ),
            ];
            Yii::$app->session->set('KCFINDER', $kcfOptions);

            $htSource = __DIR__.'/upload.htaccess';
            $htDest = $kcfinder->basePath.'/conf/upload.htaccess';
            copy($htSource, $htDest);
        }
        $options = Json::encode($this->clientOptions);
        $js[] = "tinymce.init($options);";
        if ($this->triggerSaveOnBeforeValidateForm) {
            $js[] = "$('#{$id}').parents('form').on('beforeValidate', function() { tinymce.triggerSave(); });";
        }
        $view->registerJs(implode("\n", $js));
    }
}
