<?php
use miloschuman\highcharts\Highcharts;
use yii\web\JsExpression;
use yii\helpers\Url;

use backend\models\Person;
use backend\models\Employee;
use backend\models\User;
use backend\models\ObjectFile;
use backend\models\Meeting;
use backend\models\Training;

$this->title = 'Sistem Informasi Manajemen Badan Pendidikan dan Pelatihan Keuangan';

// Ngeset perhitungan awal
if (($totalRealisasi / $totalAnggaran) < 0.7) {
    $anggaranBahaya = 'text-red';
}
else
{
    $anggaranBahaya = '';
}
// dah

?>
<div class="site-index">

    <div class="container-fluid">

        <div class="row">
            
            <div class="col-md-10">

                <div class="row">
                    
                    <div class="col-md-4">
                        
                        <div class="row">
                            <div class="col-md-12">
								<?php
								// Mencoba lebih hangat
								$sapaan = '';
								$birthday = Yii::$app->user->identity->employee->person->birthday;
								$gender = Yii::$app->user->identity->employee->person->gender;
								if(!empty($birthday)){
									$birth = new DateTime(date('Y-m-d', strtotime($birthday)));
									$now = new DateTime(date('Y-m-d'));
									$interval = $birth->diff($now);
									/* echo "difference " . $interval->y . " years, " . $interval->m." months, ".$interval->d." days "; */
									// Usia < 20 tahun => dik
									if($interval->y<20){
										$sapaan = ($gender==1)?'Dik':'Dik';
									}
									// Usia 20 - 30 tahun => Mas / Mbak
									else if($interval->y>=20 and $interval->y<=30){
										$sapaan = ($gender==1)?'Mas':'Mbak';
									}
									// Usia 31 - => Bapak / Ibu
									else if($interval->y>30){
										$sapaan = ($gender==1)?'Bapak':'Ibu';
									}
								}
								?>
								<div class="jumbotron">
								  <h1>Selamat datang!</h1>
								  <p><?php echo $sapaan.' '.ucwords(Yii::$app->user->identity->employee->person->name); ?>, sebelum mulai bekerja, pastikan data profile Anda benar dan update, </p>
								  <p><a class="btn btn-primary btn-lg" href="<?= Url::to(['user/user/profile']) ?>" role="button">Perbaharui profile</a></p>
								</div>
                            </div>
                        </div>

                    </div>
                    
                    <div class="col-md-8">
                        <div class="row">
                            <div id="slot-chart-anggaran-realisasi" class="col-md-12">
                                <?php
                                    echo Highcharts::widget([
                                        'scripts' => [
                                            'modules/exporting',
                                            'themes/grid-light',
                                        ],
                                        'options' => [
                                            'chart' => [
                                                'backgroundColor' => 'transparent'
                                            ],
                                            'title' => [
                                                'text' => '<h3>Capaian Anggaran</h3>',
                                                'align' => 'left',
                                                'useHTML' => true,
                                                'style' => [
                                                    'font-family' => 'Source Sans Pro',
                                                    'color' => '#676a6c',
                                                    'text-transform' => 'capitalize',
                                                    'font-weight' => 100
                                                ]
                                            ],
                                            'subtitle' => [
                                                'text' => '<p>Data dihitung untuk semua diklat dari semua unit tahun '.date('Y').'</p>',
                                                'align' => 'left',
                                                'useHTML' => true,
                                                'style' => [
                                                    'font-family' => 'Source Sans Pro',
                                                    'color' => '#676a6c'
                                                ]
                                            ],
                                            'xAxis' => [
                                                'categories' => ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                                                'labels' => [
                                                    'style' => [
                                                        'font-family' => 'Source Sans Pro'
                                                    ]
                                                ],
                                            ],
                                            'yAxis' => [
                                                'title' => [
                                                    'enabled' => false
                                                ],
                                                'labels' => [
                                                    'style' => [
                                                        'font-family' => 'Source Sans Pro'
                                                    ]
                                                ],
                                                'minorGridLineColor' => 'transparent'
                                            ],
                                            'credits' => [
                                                'enabled' => false
                                            ],
                                            'series' => $dataSeries
                                        ]
                                    ]);
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <h4 class="angka-penting">Rp <?php echo number_format($totalAnggaran, 2, ',', '.'); ?></h4>
                                <p class="keterangan-slot small text-muted">Total Anggaran</p>
                            </div>
                            <div class="col-md-4">
                                <h4 class="angka-penting">Rp <?php echo number_format($totalRealisasi, 2, ',', '.'); ?></h4>
                                <p class="keterangan-slot small text-muted">Total Realisasi</p>
                            </div>
                            <div class="col-md-4">
                                <h4 class="angka-penting <?php echo $anggaranBahaya; ?>">Rp <?php echo number_format(abs($totalRealisasi - $totalAnggaran), 2, ',', '.'); ?></h4>
                                <p class="keterangan-slot small text-muted">Target Anggaran yang Perlu Dikejar (Batas Aman = 70%)</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-md-2 bg-gelap panel-kanan">
                
                <div class="row">
                
                    <div class="col-md-12">
                        <div class="slot">
                            <h4 class="margin-top-large no-bold">Pegawai Online</h4>
                            <div class="online">
                                <?php
                                    foreach ($userOnline as $user) {
                                        echo '<div class="row margin-bottom-small">';

                                        // Ngambil foto
                                        $objectFile = ObjectFile::find()
                                            ->where([
                                                'object' => 'person',
                                                'object_id' => $user->person_id,
                                                'type' => 'photo'
                                            ])
                                            ->joinWith('file')
                                            ->one();
                                        // dah

                                        echo    '<div class="col-md-3 padding-right-medium">';
                                        echo        '<div class="image-frame-small">';
                                        
                                        if (empty($objectFile)) {
                                            // foto ga ada, so pake gambar default
                                            echo        '<img class="image-small" src="'.Yii::$app->homeUrl.'/logo_simbppk_pelangi.png">';
                                        }
                                        else {
                                            echo        '<img class="image-medium image-corner" src="'.Url::to(['/file/download','file'=>$objectFile->object.'/'.$objectFile->object_id.'/thumb_'.$objectFile->file->file_name]).'">';
                                        }
                                        
                                        echo        '</div>';
                                        echo    '</div>';
                                        
                                        
                                        echo    '<div class="col-md-9 padding-left-medium">';
                                        $waktuPertamaInception = new DateTime($user->time);
                                        $waktuSekarang = new DateTime(date('Y-m-d H:i:s'));
                                        $bedaWaktu = '';
                                        if ( $waktuPertamaInception->diff($waktuSekarang)->format('%H') != '00' ) {
                                            $bedaWaktu .= $waktuPertamaInception->diff($waktuSekarang)->format('%H').' jam '.$waktuPertamaInception->diff($waktuSekarang)->format('%I').' menit yang lalu';
                                        }
                                        elseif ( $waktuPertamaInception->diff($waktuSekarang)->format('%I') == '00' ) {
                                            $bedaWaktu .= 'Baru saja';
                                        }
                                        else {
                                            $bedaWaktu .= $waktuPertamaInception->diff($waktuSekarang)->format('%I').' menit yang lalu';
                                        }
                                        echo        '<p class="text-small open-sans"><strong>'.$user->person->name.'</strong>';
                                        echo            '<span class="text-small open-sans block text-muted">'.$bedaWaktu;
                                        echo            '</span>';
                                        echo        '</p>';
                                        echo    '</div>';
                                        
                                        echo '</div>';
                                    }
                                ?>
                            </div>
                        </div>

                        <div class="slot">
                            <h4 class="margin-top-large no-bold">Aktivitas Terbaru</h4>
                            <div class="aktivitas">
                                <?php
                                    foreach ($aktivitasTerbaru as $aktivitas) {
                                        echo '<div class="row margin-bottom-small">';

                                        // Jadi, semua aktivitas masuk ke 2 kategori, yaitu kalo gak create ya update
                                        // Kita tampung jenisnya, sebenarnya bisa langsung inline, tp dimasukin ke variabel
                                        // Biar tau alurnya
                                        $jenisAktivitas = 'bikin_baru';

                                        if ($aktivitas->modified > $aktivitas->created) {
                                            // Artinya user sudah melakukan perubahan
                                            $jenisAktivitas = 'perbarui';
                                        }

                                        // Ngambil foto sesuai jenis aktivitas
                                        switch ($jenisAktivitas) {
                                            case 'bikin_baru':
                                                $objectFile = ObjectFile::find()
                                                    ->where([
                                                        'object' => 'person',
                                                        'object_id' => $aktivitas->created_by,
                                                        'type' => 'photo'
                                                    ])
                                                    ->joinWith('file')
                                                    ->one();
                                                break;
                                            case 'perbarui':
                                                $objectFile = ObjectFile::find()
                                                    ->where([
                                                        'object' => 'person',
                                                        'object_id' => $aktivitas->modified_by,
                                                        'type' => 'photo'
                                                    ])
                                                    ->joinWith('file')
                                                    ->one();
                                                break;
                                            default:
                                        }
                                        // dah

                                        echo    '<div class="col-md-3 padding-right-medium">';
                                        echo        '<div class="image-frame-small">';
                                        
                                        if (empty($objectFile)) {
                                            // foto ga ada, so pake gambar default
                                            echo        '<img class="image-small" src="'.Yii::$app->homeUrl.'/logo_simbppk_pelangi.png">';
                                        }
                                        else {
                                            echo        '<img class="image-medium image-corner" src="'.Url::to(['/file/download','file'=>$objectFile->object.'/'.$objectFile->object_id.'/thumb_'.$objectFile->file->file_name]).'">';
                                        }
                                        
                                        echo        '</div>';
                                        echo    '</div>';
                                        
                                        switch ($jenisAktivitas) {											
                                            case 'bikin_baru':
												$userX = User::findOne($aktivitas->created_by);
												if(!empty($userX)){
													$employeeX = Employee::find()->where(['user_id'=>$userX->id])->one();
													if(!empty($employeeX)){
														$personX = $employeeX->person;
														echo    '<div class="col-md-9 padding-left-medium">';
														echo        '<p class="text-small open-sans"><strong>'.$personX->name.'</strong>';
														
														if (!empty($meeting = Meeting::findOne($aktivitas->id))) {
															echo            ' telah membuat rapat <span class="label label-success">baru</span>';
															echo            ' dengan nama <strong>'.$aktivitas->name.'</strong>';
														}

														if (!empty($training = Training::findOne($aktivitas->id))) {
															echo            ' telah membuat diklat <span class="label label-success">baru</span>';
															echo            ' dengan nama <strong>'.$aktivitas->name.'</strong>';
														}

														echo            '<span class="text-small open-sans block text-muted">'.date('D, d M Y', strtotime($aktivitas->created)).' jam '.date('H:i', strtotime($aktivitas->created));
														echo            '</span>';
														echo        '</p>';
														echo    '</div>';
													}
                                                }
												break;

                                            case 'perbarui':
												$userX = User::findOne($aktivitas->modified_by);
												if(!empty($userX)){
													$employeeX = Employee::find()->where(['user_id'=>$userX->id])->one();
													if(!empty($employeeX)){
														$personX = $employeeX->person;
														echo    '<div class="col-md-9 padding-left-medium">';
														echo        '<p class="text-small open-sans"><strong>'.$personX->name.'</strong>';
														
														if (!empty($meeting = Meeting::findOne($aktivitas->id))) {
															echo            ' telah melakukan <span class="label label-warning">perubahan</span> pada rapat <strong>'.$aktivitas->name.'</strong>';
														}

														if (!empty($training = Training::findOne($aktivitas->id))) {
															echo            ' telah melakukan <span class="label label-warning">perubahan</span> diklat <strong>'.$aktivitas->name.'</strong>';
														}

														echo            '<span class="text-small open-sans block text-muted">'.date('D, d M Y', strtotime($aktivitas->modified)).' jam '.date('H:i', strtotime($aktivitas->modified));
														echo            '</span>';
														echo        '</p>';
														echo    '</div>';
													}
												}
                                                break;
                                            default:
                                                echo '-';
                                        }
                                        
                                        echo '</div>';
                                    }
                                ?>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>


    </div>
</div>
<?php
    $this->registerCss('
        .container-fluid .right-side > .content {
            padding: 0px;
        }
        .site-index .container-fluid {
            padding:17px 17px 0px 17px;
        }
        .site-index .container-fluid > .row {
            margin-left: -17px;
            margin-right: -17px;
        }
        .site-index h2, .site-index h3 {
            letter-spacing: -1px;
            margin:0px;
        }
        .text-red {
            animation: berdenyut 1s infinite;
            -webkit-animation: berdenyut 1s infinite;
        }
        @keyframes berdenyut {
            50% {
                color: #676a6c;
            }
        }
        @-webkit-keyframes berdenyut {
            50% {
                color: #676a6c;
            }
        }
        .panel-kanan {
            margin-top: -18px;
            min-height: 500px;
        }
        .slimScrollBar {
            border-radius: 3px;
        }
    ');

    $this->registerJs("
        $(function(){
            $('.aktivitas').slimScroll({
                height: '300px',
                railVisible: true
            });
            $('.online').slimScroll({
                height: '200px',
                railVisible: true
            });
        });
    ");
?>