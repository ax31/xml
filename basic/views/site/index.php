<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */

$this->title = 'File';
$form = ActiveForm::begin([
    'id' => 'file-form',
    'options' => ['enctype' => 'multipart/form-data']
]) ?>
<?= $form->field($model, 'xml')->fileInput(); ?>
<div class="form-group">
    <?= Html::submitButton('Загрузить', ['class' => 'btn btn-primary']) ?>
</div>
<?php ActiveForm::end() ?>
<?php if ($files): ?>
    <table class="table table-striped table-hover">
        <tr>
            <th>Номер</th>
            <th>Дата загрузки</th>
            <th>Название</th>
        </tr>
        <?php foreach ($files as $file): ?>
            <tr>
                <td><?= $file->primaryKey ?></td>
                <td><?= $file->date_upload ?></td>
                <td><?= Html::a($file->title, ['site/file', 'id' => $file->primaryKey]) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>
<p class="lead">Количество файлов, у которых тэгов больше 20: <?= $countFiles ?></p>
