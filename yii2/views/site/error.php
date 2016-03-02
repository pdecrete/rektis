<?php
/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->context->layout = 'info';

$this->title = $name;
?>
<div class="well" style="color: black;">
    <?= nl2br(Html::encode($message)) ?>
    <?php if (!Yii::$app->user->isGuest && (Yii::$app->user->can('superadmin') || Yii::$app->user->can('admin'))) : ?> 
        <pre><?= $exception; ?></pre>
    <?php endif; ?>
</div>
<p>
    Το παραπάνω λάθος προέκυψε κατά τη διάρκεια εξυπηρέτησης του αιτήματος σας.
    Παρακαλώ επικοινωνήστε με το διαχειριστή σε περίπτωση που το πρόβλημα 
    επιμένει ή σε περίπτωση που πιστεύετε ότι το πρόβλημα αφορά λανθασμένες
    ρυθμίσεις ή λανθασμένη απαγόρευση πρόσβασης.
</p>
