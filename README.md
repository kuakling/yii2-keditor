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

```php
<?= $form->field($model, 'detail')->widget(
  \kuakling\keditor\CKEditor::className(), 
  [
    'filemanager'=>true, //true = enabled kcfinder, false = disabled kcfinder
    'preset'=>'full' //toolbar -> basic, standard, full
  ]
)->label(false); ?>```
