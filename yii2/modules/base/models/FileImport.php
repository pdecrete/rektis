<?php

namespace app\modules\base\models;

use Exception;
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
    public function rules($extensions = 'xls,xlsx,ods', $maxSize = 4*1024*1024)
    {
        return [[['importfile'], 'required'],
                [['importfile'], 'safe'],
                [['importfile'], 'file', 'extensions' => $extensions],
                [['importfile'], 'file', 'maxSize' => $maxSize],
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
    public function upload($importfolder)
    {   
        try {
            $this->importfile = \yii\web\UploadedFile::getInstance($this, 'importfile');
            
            if ($this->validate()) { 
                $path = Yii::getAlias($importfolder);
                if (!is_writeable($path)) {
                    throw new Exception("Upload folder (" . $importfolder . ") is not writable");
                }
                if (empty($this->importfile->saveAs($path . $this->importfile))) {
                    throw new Exception("The file is empty");
                }                
                //return true;
            } 
            else {
                echo "<pre>"; print_r($this->errors); echo "</pre>"; die();                
                throw new Exception("<pre>" . print_r($this->errors) . "</pre>");
            }
        }
        catch(Exception $exc) {
            Yii::$app->session->addFlash('danger', Yii::t('app', $exc->getMessage()));
            //return false;
        }
    }
}
