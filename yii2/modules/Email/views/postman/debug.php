<?php 

use yii\bootstrap\Html;
use yii\helpers\VarDumper;

//
?>
<h1>Debug</h1>
<pre><?php echo VarDumper::dumpAsString($subject); ?></pre>
<pre><?php echo VarDumper::dumpAsString($body); ?></pre>
<pre><?php echo VarDumper::dumpAsString($data); ?></pre>
