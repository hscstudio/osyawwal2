<?php
use Yii\helpers\Html;
?>

<table class="table table-bordered table-condensed table-hover">
	<tbody>
		<tr>
			<th style="width:50px;">NO</th>
			<th>JENIS DOKUMEN</th>
			<th style="width:100px;">AKSI</th>
		</tr>
		
		<tr>
			<td></td>
			<td colspan="2" style="font-weight:bold; ">DOKUMEN UMUM</td>
		</tr>
		
		<!-- <tr>
			<td>1</td>
			<td>Surat Tugas Terkait Diklat</td>
			<td>
				<a class="btn btn-success btn-xs" title="Cetak Surat Tugas Terkait Diklat" onclick="viewSuratTugasDiklat(967)">
				<i class="fa fa-fw fa-download"></i> Cetak 
		  	</a>		  	
			</td>
		</tr> -->
		
		<!-- <tr>
			<td>2</td>
			<td>Checklist Data Peserta (Pengumuman Hasil/Sertifikat)</td>
			<td>
				<a class="btn btn-success btn-xs" title="Cetak Checklist Data Peserta" href="php/evaluation/training.student.checklist.excel.2.php?id=967">
				<i class="fa fa-fw fa-download"></i> Cetak 
		  	</a>
		  	
			</td>
		</tr> -->
		
		<!-- <tr>
			<td>3</td>
			<td>Checklist Pembuatan Sertifikat</td>
			<td>
				<a class="btn btn-success btn-xs" title="Cetak Checklist Pembuatan Sertifikat" onclick="viewChecklistPembuatanSertifikat(967)">
				<i class="fa fa-fw fa-download"></i> Cetak 
		  	</a>		  	
			</td>
		</tr> -->
		
		<tr>
			<td>4</td>
			<td>Tanda Terima Sertifikat</td>
			<td>
				<?php
					echo Html::a('<i class="fa fa-fw fa-download"></i>Cetak', ['dokumen-certificate-receipt', 'activity_id'=>$activity_id], [
						'class'=>'btn btn-success btn-xs',
						'data-pjax'=>'0',
					]);
				?>		  	
			</td>
		</tr>
		
		<!-- <tr>
			<td>5</td>
			<td>Surat Pengantar Sertifikat Pengajar</td>
			<td>
				<a class="btn btn-success btn-xs" title="Cetak Pengantar Sertifikat Pengajar" onclick="viewPengantarSertifikatPengajar(967)">
				<i class="fa fa-fw fa-download"></i> Cetak 
		  	</a>		  	
			</td>
		</tr> -->
		
		<!-- <tr>
			<td>6</td>
			<td>Sertifikat Pengajar / Piagam Penghargaan</td>
			<td>
				<a class="btn btn-success btn-xs" title="Cetak Sertifikat Pengajar" onclick="viewSertifikatPengajar(967)">
				<i class="fa fa-fw fa-download"></i> Cetak 
		  	</a>		  	
			</td>
		</tr> -->
		
		<!-- <tr>
			<td>7</td>
			<td>Database Alumni Diklat</td>
			<td>
				<a class="btn btn-success btn-xs" title="Cetak Database Alumni Diklat" href="php/evaluation/training.student.database.excel.2.php?id=967">
				<i class="fa fa-fw fa-download"></i> Cetak 
		  	</a>		  	
			</td>
		</tr> -->
		
	</tbody>
</table>