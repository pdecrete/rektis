<?php

use yii\bootstrap\Html;
use yii\grid\GridView;
use app\modules\SubstituteTeacher\models\TeacherRegistry;
use app\modules\SubstituteTeacher\models\Teacher;
use kartik\select2\Select2;
use yii\bootstrap\ButtonDropdown;
use yii\bootstrap\Modal;
use yii\bootstrap\ActiveForm;
use app\components\FilterActionColumn;
use app\modules\SubstituteTeacher\models\Specialisation;
use kartik\date\DatePicker;
use yii\web\View;
use yii\widgets\Pjax;
use yii\helpers\Url;


$bundle = \app\modules\SubstituteTeacher\assets\ModuleAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel app\modules\SubstituteTeacher\models\TeacherSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('substituteteacher', 'Teachers');
$this->params['breadcrumbs'][] = $this->title;
?>


    <script>

    function getRows(reptype, mode, pn, pd, kat)
    {
        //alert(mode);        
        //alert(pn);
        //alert(pd);
        //alert(kat);
        

        var keys = $('#stgrid').yiiGridView('getSelectedRows');
             var favorite = [];
            $("input:checkbox[name='selection[]']:checked").each(function(){   
                favorite.push($(this).val());       
            });
            
            //Ajax Function to send a get request
            var jsonString = JSON.stringify(favorite);
            
            //alert(jsonString); 

            var url = reptype;//'<?php echo Yii::$app->request->baseUrl."/SubstituteTeacher/teacher/" ?>' + reptype + '/';
            //alert(url);
            var ext = (mode=='pdf')?'.pdf':'.docx';
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {data : jsonString, mode: JSON.stringify(mode), pn : JSON.stringify(pn), pd : JSON.stringify(pd), kat:JSON.stringify(kat)},
                    cache: false,
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function (response) {
                        var a = document.createElement('a');
                        var url = window.URL.createObjectURL(response);
                        a.href = url;
                        a.download = reptype + ext;
                        document.body.append(a);
                        a.click();
                        a.remove();
                        window.URL.revokeObjectURL(url);
                    }                
                });
            }    
    </script>
    
<div class="teacher-mkchange">
    <h1>
        <?= Html::encode($this->title) ?>
    </h1>

    <div class="btn-group-container">        
            <p>
<!--                <button type="button" onclick="getRows('mkchangereport','','')" class="btn btn-success"><?= Yii::t('substituteteacher', 'MK Report')?> </button>-->
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#get_protocol"><?= Yii::t('substituteteacher', 'MK Decision')?></button>
<!--                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#get_protocol2"><?= Yii::t('substituteteacher', 'MK Decision Word')?></button>-->
            </p>    
    </div>


    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'id' => 'stgrid',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,

        'rowOptions' => function ($model, $key, $index, $grid) {
            if ($model->status == Teacher::TEACHER_STATUS_NEGATION) {
                return ['class' => 'danger'];
            } elseif ($model->status == Teacher::TEACHER_STATUS_APPOINTED) {
                return ['class' => 'success'];
            } elseif ($model->status == Teacher::TEACHER_STATUS_PENDING) {
                return ['class' => 'warning'];
            }
        },
    
        'columns' => [
            //['class' => 'yii\grid\CheckboxColumn'],
            ['class' => 'yii\grid\CheckboxColumn',
                'checkboxOptions' => function ($model) {return ['value' => $model->id];}
            ],            
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            [
                //'attribute' => 'registry_id',
                'label' => Yii::t('substituteteacher', 'Teacher'),
                'value' => function ($model) {
                    return Html::a(Html::icon('user'), ['teacher-registry/view', 'id' => $model->registry_id], ['class' => 'btn btn-xs btn-default', 'title' => Yii::t('substituteteacher', 'View registry entry')]) . ' ' . $model->registry->name;
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'registry_id',
                    'data' => TeacherRegistry::selectables('id', 'name'),
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => ['placeholder' => '...'],
                    'pluginOptions' => ['allowClear' => true],
                ]),
                'format' => 'html'
            ],
            [
                'attribute' => 'specialisation_id',
                'label' => Yii::t('substituteteacher', 'Specialisation'),
                'value' => function ($m) {
                    $all_labels = $m->registry->specialisation_labels;
                     return empty($all_labels) ? null : implode('<br/>', $all_labels);
                },
                'format' => 'html',
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'specialisation_id',
                    'data' => Specialisation::selectables(),
                    'theme' => Select2::THEME_BOOTSTRAP,
                    'options' => ['placeholder' => '...'],
                    'pluginOptions' => ['allowClear' => true],
                ]),
            ],
            'year',                      
            [
                'attribute' => 'mk_changedate',
                'format' => ['date', 'php:Y-m-d']
            ],
            [
                 'attribute' => 'sector',
                'label' => Yii::t('substituteteacher', 'Placement'),
                'value' => function ($m) {
                    return $m->sectorlabel;
                },
            ],
                        
//            [
//                'attribute' => 'mk_changedate',
//                'format' => ['date', 'php:Y-m-d']
//            ],                        
//            [
//                'attribute' => Yii::t('substituteteacher', 'Status'),    
//                'value' => 'status_label',
//                'filter' => Select2::widget([
//                     'model' => $searchModel,
//                     'attribute' => 'status',
//                     'data' => Teacher::getChoices('status'),
//                     'theme' => Select2::THEME_BOOTSTRAP,
//                     'options' => ['placeholder' => '...'],
//                     'pluginOptions' => ['allowClear' => true],
//                 ]),                
//            ],
                        
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
    
<?php $url = Url::to(['teacher/mkchangedecision']);?>
<div class="modal fade" id="get_protocol" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Πρωτόκολλο Απόφασης</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <div id="protocolo" class="pclass">
                Αριθμός :<input type="number" id="pn" min="1"> Ημερομηνία : <input type="date" id="pd"> Κατάταξη; <input type="checkbox" id="kat"> 
            </div>             
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Κλείσιμο</button>
        <button type="button" class="btn btn-primary" onclick="getRows('<?php echo $url; ?>', 'pdf', document.getElementById('pn').value.toString(), document.getElementById('pd').value.toString(), document.getElementById('kat').checked.toString())" data-dismiss="modal">Συνέχεια</button>
      </div>
    </div>
  </div>
</div>    
    
<!-- 
<div class="modal fade" id="get_protocol2" tabindex="-1" role="dialog" aria-labelledby="example2ModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="example2ModalLabel">Πρωτόκολλο Απόφασης Word</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <div id="protocolo2" class="pclass">
                Αριθμός :<input type="number" id="pn2" min="1"> Ημερομηνία : <input type="date" id="pd2"> 
            </div>             
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Κλείσιμο</button>
        <button type="button" class="btn btn-primary" onclick="getRows('mkchangedecision', 'word', document.getElementById('pn2').value.toString(), document.getElementById('pd2').value.toString())" data-dismiss="modal">Συνέχεια</button>
      </div>
    </div>
  </div>
</div>        -->