<?php
namespace app\modules\eduinventory\components;

use DateTime;
use Yii;
use yii\helpers\ArrayHelper;
use app\models\Specialisation;

class EduinventoryHelper
{
    /*
     * Returns the starting year of the school year expressed by $strDate.
     * $strDate is in string format.
     * For example if $strDate is 21/02/2018 the return value will be 2017.
     * If $date is 15/09/2018 the return value is 2018.
     */
    public static function getSchoolYearOf($strDate)
    {
        $date = DateTime::createFromFormat("Y-m-d", $strDate);
        $year = $date->format("Y");
        $month = $date->format("m");
        $day = $date->format("d");
        if (!checkdate($month, $day, $year)) {
            return -1;
        }
        return ($month >= 9) ? $year : $year-1;
    }
    
    /**
     * Returns an array with the prefectures options.
     * The prefectures' options are retrieved from the params.php file 
     *
     * @return string[]
     */
    public static function getPrefectures()
    {        
        return Yii::$app->getModule('eduinventory')->params['prefectures'];
    }
    
    /**
     * Returns the educational levels options.
     * The educational levels' options are retrieved from the params.php file
     *
     * @return string[]
     */
    public static function getEducationalLevels()
    {
        return Yii::$app->getModule('eduinventory')->params['education_levels'];
    }
    
    /**
     * Returns the teacher specializations in a mapping (specialization_id => specialization_code) form.
     *
     * @return string[]
     */
    public static function getSpecializations()
    {        
        return ArrayHelper::map(Specialisation::find()->all(), 'id', 'code');
    }
}