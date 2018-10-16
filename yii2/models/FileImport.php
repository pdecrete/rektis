<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * 
 * @property string $excelfile_disposals
 * 
 */
class FileImport extends Model
{   
    public $importfile;
    
    /**
     * @inheritdoc
     */
    public function rules($extensions = 'xls, xlsx', $maxSize = '10000000')
    {
        return [[['importfile'], 'required'],
            [['importfile'], 'safe'],
            [['importfile'], 'file', 'extensions' => $extensions],
            [['importfile'], 'file', 'maxSize' => $maxSize]
        ];
    }
    
    /**
     * @inheritdoc
     */
    
    public function attributeLabels()
    {
        return ['$importfile' => Yii::t('app', 'Αρχείο')];
    }
    
    /**
     * Uploads the disposals Excel file to the server.
     */
    public function upload()
    {        
        //$import_model->excelfile_disposals = \yii\web\UploadedFile::getInstance($import_model, 'excelfile_disposals');
        $this->importfile = \yii\web\UploadedFile::getInstance($this, 'importfile');
        if ($this->validate()) {
            $path = Yii::getAlias(Yii::$app->controller->module->params['disposal_importfolder']);
            if (!is_writeable($path)) {
                return false;
            }
            if (empty($this->importfile->saveAs($path . $this->importfile))) {
                return false;
            }
            
            return true;
        } 
        else {
            return false;
        }
    }
}
