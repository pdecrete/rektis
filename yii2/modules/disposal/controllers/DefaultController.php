<?php

namespace app\modules\disposal\controllers;

use yii\web\Controller;
use app\modules\Pages\models\Page;
use app\modules\disposal\DisposalModule;
use Yii;

/**
 * Default controller for the `disposal` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    public function actionHelp($helpId = 1)
    {
        $legislation_help = Page::findOne(['identity' => 'disposal_legislationhelp']);
        $app_help = Page::findOne(['identity' => 'disposal_apphelp']);
        $approval_help = Page::findOne(['identity' => 'disposal_approvalhelp']);
        if(is_null($app_help)){
            $app_help['title'] = 'Η σελίδα βοήθειας με λεκτικό αναγνωριστικό <em>"disposal_apphelp"</em> δεν βρέθηκε.';
            $app_help['content'] = '';
        }
        
        if(is_null($approval_help)){
            $approval_help['title'] = 'Η σελίδα βοήθειας με λεκτικό αναγνωριστικό <em>"disposal_approvalhelp"</em> δεν βρέθηκε.';
            $approval_help['content'] = '';
            $approval_help['id'] = -1;
            $approval_help['updated_at'] = -1;
        }
        return $this->render('help', ['helpId' => $helpId, 'app_help' => $app_help, 'legislation_help' => $legislation_help, 'approval_help' => $approval_help]);
    }
    
    public function actionLegislation($fileId)
    {
        $files = [  1 => 'FEK_1340_2002.pdf', 2 => 'FEK_26_2007.pdf', 3 => 'FEK_235_2013.pdf', 4 => 'FEK_83_2016.pdf'];
        
        if (!array_key_exists($fileId, $files)) {
            Yii::$app->session->addFlash('danger', DisposalModule::t('modules/disposal/app', "Error in downloading file."));
            return $this->redirect(['help']);
        }
        
        
        $filedownload = Yii::getAlias("@vendor/admapp/resources/disposals/legislation/" . $files[$fileId]);
        return Yii::$app->response->SendFile($filedownload);
    }
}
