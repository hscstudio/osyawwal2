<?php
use kartik\helpers\Html;
$controller = $this->context;
$menus = $controller->module->getMenuItems();
$this->params['sideMenu'][$controller->module->uniqueId]=$menus;
?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <i class="fa fa-sitemap fa-fw"></i> Struktur Organisasi
        </div>
        <div class="panel-body">
            <div class="tree" style="zoom: 1;overflow:hidden;">
                <ul>
                    <li>
                        <?php
                        $data = \backend\models\Organisation::find()
                            ->where([
                                'KD_UNIT_ES2'=>'13',
                                'KD_UNIT_ES3'=>'04',
                                'KD_ESELON'=>'3',
                            ])
                            ->one();
                        $eselon = '<strong>'.$data->NM_UNIT_ORG.'</strong><br>';
                        $employee = \backend\models\Employee::find()
                            ->where([
                                'organisation_id'=>$data->ID,
                                'chairman'=>1,
                            ])
                            ->one();
                        if(null!=$employee) {
                            echo Html::a($eselon.$employee->person->name, ['view-employee', 'id' => $employee->person_id], [
                                'class' => 'level1 modal-heart',
                                'modal-size' => 'modal-lg',
                                'modal-title' => 'Detail Employee'
                            ]);
                            echo '<br>';
                        }
                        else{
                            echo '<a class="level1">';
                            echo $eselon;
                            echo '</a>';
                        }
                        echo '<ul>';
                        $datas2 = \backend\models\Organisation::find()
                            ->where([
                                'KD_UNIT_ES2'=>$data->KD_UNIT_ES2,
                                'KD_UNIT_ES3'=>$data->KD_UNIT_ES3,
                                'KD_ESELON'=>'4',
                            ])
                            ->all();
                        foreach($datas2 as $data2){
                            echo '<li>';
                            $eselon = '<strong>'.$data2->NM_UNIT_ORG.'</strong><br>';
                            $employee = \backend\models\Employee::find()
                                ->where([
                                    'organisation_id'=>$data2->ID,
                                    'chairman'=>1,
                                ])
                                ->one();
                            if(null!=$employee) {
                                echo Html::a($eselon.$employee->person->name, ['view-employee', 'id' => $employee->person_id], [
                                    'class' => 'level2 modal-heart',
                                    'modal-size' => 'modal-lg',
                                    'modal-title' => 'Detail Employee'
                                ]);
                                echo '<br>';
                            }
                            else{
                                echo '<a class="level2">';
                                echo $eselon;
                                echo '</a>';
                            }
                            echo '<ul>';
                            $employees = \backend\models\Employee::find()
                                ->where([
                                    'organisation_id'=>$data2->ID,
                                    'chairman'=>0,
                                ])
                                ->all();
                            echo '<li>';
                            $idx=1;
                            foreach($employees as $employee){
                                echo Html::a($idx++.'. '.$employee->person->name,['view-employee','id'=>$employee->person_id],[
                                    'class'=>'level3 modal-heart',
                                    'modal-size'=>'modal-lg',
                                    'modal-title' => 'Detail Employee'
                                ]);
                                echo '<br>';

                            }
                            if($idx==1){
                                echo Html::a('-','#',[
                                    'class'=>'level3'
                                ]);
                                echo '<br>';
                            }
                            echo '</li>';
                            echo '</ul>';
                            echo '</li>';
                        }
                        echo '</ul>';
                        ?>
                    </li>
                </ul>
            </div>
        </div>
        <div class="panel-footer">
            Copyright &copy;  2014 by Syawwal&trade; Dev Team
        </div>
    </div>

<?= \hscstudio\heart\widgets\Modal::widget() ?>