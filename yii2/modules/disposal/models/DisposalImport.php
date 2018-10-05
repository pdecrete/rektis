<?php

namespace app\modules\disposal\models;

use app\modules\disposal\DisposalModule;
use Yii;
use yii\base\Model;

/**
 * 
 * @property string $excelfile_disposals
 * 
 */
class DisposalImport extends Model
{   
    public $excelfile_disposals;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [[['excelfile_disposals'], 'required'],
            [['excelfile_disposals'], 'safe'],
            [['excelfile_disposals'], 'file', 'extensions'=>'xls, xlsx'],
            [['excelfile_disposals'], 'file', 'maxSize'=>'10000000']
        ];
    }
    
    /**
     * @inheritdoc
     */
    
    public function attributeLabels()
    {
        return ['excelfile_disposals' => DisposalModule::t('modules/disposal/app', 'Αρχείο')];
    }
    
    /**
     * Uploads the disposals Excel file to the server.
     */
    public function upload()
    {        
        if ($this->validate()) {
            $path = Yii::getAlias(Yii::$app->controller->module->params['disposal_importfolder']);
            if (!is_writeable($path)) {
                return false;
            }
            if (empty($this->excelfile_disposals->saveAs($path . $this->excelfile_disposals))) {
                return false;
            }
            
            return true;
        } 
        else {
            return false;
        }
    }
}
