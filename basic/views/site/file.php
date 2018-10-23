<?php
$this->title = $model->title;
?>
<h1><?= $model->title ?></h1>
<ul class="list-unstyled">
    <?php foreach ($model->tags as $tag): ?>
        <li><?= $tag->tagname ?> - <?= $tag->number ?></li>
    <?php endforeach; ?>
</ul>
