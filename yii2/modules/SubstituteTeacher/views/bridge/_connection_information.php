<?php

use yii\helpers\VarDumper;

?>
<p>
    <div class="well well-sm">
        <?= Yii::t('substituteteacher', 'Connection options') ?>:
        <?= VarDumper::dumpAsString($connection_options) ?>
    </div>
</p>