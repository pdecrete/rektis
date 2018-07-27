<?php

use app\modules\SubstituteTeacher\models\BaseImportModel;

/**
 * @param $worksheet
 * @param $line_limit
 * @param $startRow
 * @param $highestRow
 * @param $highestColumnIndex
 */

if (!isset($startRow)) {
    $startRow = 1;
}

?>
<?php if ($startRow < 0 || $highestRow < 1) : ?>
<?= \Yii::t('substituteteacher', 'There seems to be no data in the worksheet.') ?>
<?php else : ?>
<div class="table-responsive">
    <table class="table table-hover">
        <caption>
            <?= \Yii::t('substituteteacher', 'Data preview; Displaying {line_limit} lines; {max_lines} lines in worksheet.', ['line_limit' => $line_limit, 'max_lines' => $highestRow]) ?>
        </caption>
        <thead>
            <tr>
                <th>&nbsp;</th>
                <?php
                    $clbl = 'A';
                    for ($i = 1; $i <= $highestColumnIndex; $i++) :
                        ?>
                <th>
                    <?php echo $clbl++; ?>
                </th>
                <?php endfor; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($worksheet->getRowIterator($startRow) as $row) : ?>
            <?php $row_index = $row->getRowIndex(); ?>
            <tr>
                <th>
                    <?php echo $row_index; ?>
                </th>
                <?php
                        $cellIterator = $row->getCellIterator();
                        $cellIterator->setIterateOnlyExistingCells(false);
                        foreach ($cellIterator as $cell) :
                            $column_ltr = $cell->getColumn();
                            $col = PHPExcel_Cell::columnIndexFromString($column_ltr);
                            $row = $cell->getRow();

                            $cell_value = $cell->getValue();
                            $calc_value = BaseImportModel::getCalculatedValue($cell);
                            $cell_is_set = (!is_null($cell_value) && trim($calc_value) != '');

                            $hint_class = '';
                            if (!$cell_is_set) {
                                $hint_class = 'warning';
                            }

                            ?>
                <td class="<?php echo $hint_class; ?>">
                    <?php echo $calc_value, ((BaseImportModel::isFormula($cell_value)) ? " <span class=\"text-info\">formula</span>" : ""); ?>
                </td>
                <?php endforeach; ?>
            </tr>
            <?php
            if ($row_index >= $line_limit) {
                break;
            } ?>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif;
