# yii2-keditor
CKEditor and KCFinder
Keditor
=======
CKEditor and KCFinder

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist kuakling/yii2-keditor "*"
```

or add

```
"kuakling/yii2-keditor": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by :

Convert textarea to CKEditor
```php
<?php
//CKEditor
echo $form->field($model, 'detail')->widget(
  \kuakling\keditor\CKEditor::className(), 
  [
    'uploadDir' => '/var/www/public_html/UserFiles',
    'uploadURL' => '/UserFiles/',
    'filemanager'=>true, //true = enabled kcfinder, false = disabled kcfinder
    'preset'=>'full' //toolbar -> basic, standard, full
  ]
)->label(false); ?>
```

```php
<?php
//TinyMce
echo $form->field($model, 'detail')->widget(
  \kuakling\keditor\TinyMce::className(), 
  [
    'uploadDir' => '/var/www/public_html/UserFiles',
    'uploadURL' => '/UserFiles/',
    'enableFilemanager' => true,
    'folderName' => ['file'=> 'File', 'image'=>'Image', 'media'=>'Media'],
  ]
); ?>
```

Using textinput choose file in server and return filename to textinput by CKFinder
```php
<?php 
echo $form->field($model, 'icon')->widget(
  \kuakling\keditor\KCFinderTextInput::className(),
  [
    'dir' => 'icons' //sub directory of kcfinder upload directory
  ]
)->label(false);
?>
```
