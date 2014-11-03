<?php
use backend\models\Person;
use miloschuman\highcharts\Highcharts;
use yii\web\JsExpression;

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
                                <h2>Selamat datang <?php echo ucwords(Person::findOne(Yii::$app->user->identity->id)->name); ?></h2>
                                <p>Anda memiliki 10 tugas, 5 pesan, dan 20 notifikasi yang belum dibaca</p>
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
                        <h4 class="margin-top-large no-bold">Pegawai Online</h4>
                        <?php
                            foreach ($userOnline as $user) {
                                echo '<div class="row margin-bottom-small">';
                                
                                echo    '<div class="col-md-3 padding-right-medium">';
                                echo        '<div class="image-frame-small">';
                                echo            '<img class="image-small" src="'.Yii::$app->homeUrl.'/logo_simbppk_pelangi.png">';
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
            padding:17px;
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
    ');
?>