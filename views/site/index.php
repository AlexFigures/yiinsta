<?php

/* @var $this yii\web\View */

$this->title = 'Instagram outbox';


use yii\bootstrap\ActiveForm;
use yii\helpers\Html;?>

<div class="site-index">
    <p>Введите пользователей, последние фото которых хотите загрузить через запятую:</p>

    <?php $form = ActiveForm::begin([
        'id' => 'insta-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

    <?= $form->field($model, 'usernames')->textInput(['autofocus' => true]) ?>

    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('Download', ['class' => 'btn btn-primary', 'name' => 'download-button']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
    <?php

    if ($model->usernames) {
        $accaunts = explode(',', preg_replace('/\s+/', '', $model->usernames));
        foreach ($accaunts as $acc) {
            $results_array = $model->getInstPost($acc);
            if ($results_array === false) {
                echo '<div class="gallery"><a></a><div class="desc">Пользователь: ' . $acc . ' не найден или закрыл доступ</div></div><br>';
            } else {
                echo '<div class="gallery"><a target="_blank" href="http://instagram.com/p/' . $results_array['node']['shortcode'] . '"><img src="' . $results_array['node']['display_url'] . '"></a><div class="desc">Latest Photo by: ' . $acc . ' Likes: ' . $results_array['node']['edge_liked_by']['count'] . ' - Comments: ' . $results_array['node']['edge_media_to_comment']['count'] . '</div></div>';
            }
        }
    }
    ?>
</div>
