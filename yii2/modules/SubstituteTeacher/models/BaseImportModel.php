<?php
namespace app\modules\SubstituteTeacher\models;

use \Yii;
use yii\base\Model;
use PHPExcel_IOFactory;
use PHPExcel_Shared_Date;

/**
 * Description of BaseImportModel
 *
 */
class BaseImportModel extends Model
{
    public $filename;
    public $phpexcelfile;
    public $fileinfo;
    public $worksheets;
    private $by_class_subdirs = [
        'Import' => 'incident',
        'ImportThreat' => 'threat',
        'ImportXLS' => 'xlscellranges',
        '-' => '.',
    ];

    public function rules()
    {
        return [
        ];
    }

    public function attributeLabels()
    {
        return [
        ];
    }

    /**
     * Get file properties.
     *
     * @param string $file the filename of the file to open
     * @return boolean true if loading succeeds
     */
    public function find($file = null)
    {
        if ($file !== null) {
            $this->filename = $file;
        }

        if (is_file($this->filename)) {
            $inputFileName = $this->filename;
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);

            $objReader = PHPExcel_IOFactory::createReader($inputFileType);

            $this->phpexcelfile = $objReader->load($inputFileName);
            $properties = $this->phpexcelfile->getProperties();

            $this->fileinfo = [
                Yii::t('substituteteacher', "Title") => $properties->getTitle(),
                Yii::t('substituteteacher', "Filename") => pathinfo($this->filename, PATHINFO_BASENAME),
                Yii::t('substituteteacher', "Subject") => $properties->getSubject(),
                Yii::t('substituteteacher', "Creator") => str_replace(';', '; ', $properties->getCreator()),
                Yii::t('substituteteacher', "Created at") => date('d/m/Y H:i:s', $properties->getCreated()),
                Yii::t('substituteteacher', "Modified at") => date('d/m/Y H:i:s', $properties->getModified()),
                Yii::t('substituteteacher', "Number of worksheets") => $this->phpexcelfile->getSheetCount(),
            ];

            $this->worksheets = $this->phpexcelfile->getSheetNames();
        } else {
            $this->filename = null;
            $this->fileinfo = [];
            $this->worksheets = [];
        }
        return true;
    }

    public static function getCalculatedValue($cell)
    {
        $cell_value = $cell->getValue();
        if (!is_null($cell_value)) {
            try {
                $calc_cell_value = $cell->getCalculatedValue();
            } catch (Exception $e) {
                $calc_cell_value = $cell->getOldCalculatedValue();
            }
            if (trim($calc_cell_value) != '' && PHPExcel_Shared_Date::isDateTime($cell)) {
                $calc_cell_value = date('Y-m-d H:i:s', PHPExcel_Shared_Date::ExcelToPHP($calc_cell_value));
            }
        } else {
            $calc_cell_value = $cell_value;
        }

        return trim($calc_cell_value);
    }

    public static function isFormula($v = null)
    {
        if (is_string($v) && mb_strlen($v) > 0 && $v[0] == '=') {
            return true;
        } else {
            return false;
        }
    }
}
