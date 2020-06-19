<div class="ibox-title">
    <h2>Bobot akhir</h2>
</div>
<?php
if(count($kriteria_list)==0)
{
	die('Mohon maaf, Silahkan input kriteria terlebih dahulu');
}
if(count($alternatif_list)==0)
{
	die('Mohon maaf, Silahkan input alternatif terlebih dahulu');
}
if(count($bobot_kriteria)==0)
{
	die('Mohon maaf, bobot kriteria tidak ditemukan, silahkan lakukan perhitungan fuzzy terlebih dahulu');
}
if(count($data_alternatif)!=count($alternatif_list))
{
	die('Mohon maaf, bobot alternatif tidak ditemukan, silahkan lakukan perhitungan fuzzy terlebih dahulu');
} 
foreach($alternatif_list as $key_alternatif=>$row_alternatif){
$left   = $row_alternatif['KodeAlternatif'];
	foreach($kriteria_list as $key_kriteria=>$row_kriteria){
			$right = $row_kriteria['KodeKriteria'];
			if(!isset($data_alternatif[$left][$right]))
			{
				die('Mohon maaf, nilai kriteria / alternatif belum lengkap, silahkan lengkapi data dan lakukan perhitungan fuzzy');
			}
		}
	}
?>
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>Tabel Matriks Keputusan</h5>
            <div class="ibox-tools">
            </div>
        </div>
        <div class="ibox-content" style="overflow: auto;">
            <table class="table table-bordered">
                <thead class="">
        <tr>
            <th style="text-align:center;vertical-align:middle" rowspan="2" colspan="1" >
                Alternatif
            </th>
            <th style="text-align:center;" rowspan="1" colspan="<?=count($kriteria_list)?>">
                Kriteria
            </th>
        </tr>
        <tr>
			<?php foreach($kriteria_list as $key_kriteria=>$row_kriteria){?>
				<th style="text-align: center;"><?=$row_kriteria['KodeKriteria']?></th>
			<?php }?>
        </tr>
                </thead>
                <tbody>
                    <?php 
                        foreach($alternatif_list as $key_alternatif=>$row_alternatif){
                        $left   = $row_alternatif['KodeAlternatif'];
                        ?>
                        <tr>
                            <td><strong title="<?=$left?>"><?=$left?></strong></td>
                            <?php
                            foreach($kriteria_list as $key_kriteria=>$row_kriteria){
                                $right = $row_kriteria['KodeKriteria'];
								$bobot = $data_alternatif[$left][$right]['Value'];
								$bobot_pow = $data_alternatif[$left][$right]['Value']**2;
								if($kriteria_code[$right]['Kode'] == $right)
								{
									$kriteria_code[$right]['SumBobot'] = $kriteria_code[$right]['SumBobot']+$bobot_pow;
								}
                                ?>
                                <td style="text-align: center;"><?=$bobot;?></td>
                            <?php }?>
                        </tr>
                    <?php }
                    ?>
                </tbody>
				<tfoot style="background-color: #F5F5F6;">
					<tr>
						<td>
							<strong>Bobot Kriteria</strong>
						</td>
						<?php foreach($kriteria_list as $key_kriteria=>$row_kriteria){
							$right = $row_kriteria['KodeKriteria'];
							$bobot_kriteria = $data_kriteria[$right]['Value'];
							?>
							<td style="text-align: center;"><strong><?=$bobot_kriteria?></strong></td>
						<?php }?>
					</tr>
					<tr>
						<td>
							<strong>Bobot Pembagi</strong>
						</td>
						<?php foreach($kriteria_list as $key_kriteria=>$row_kriteria){
							$right = $row_kriteria['KodeKriteria'];
							if($kriteria_code[$right]['Kode'] == $right)
							{
								$kriteria_code[$right]['BobotPembagi'] = sqrt($kriteria_code[$right]['SumBobot']);
							}
							?>
							<td style="text-align: center;"><strong><?=number_format($kriteria_code[$right]['BobotPembagi'],4,'.','.')?></strong></td>
						<?php }?>
					</tr>
				</tfoot>
            </table>
        </div>
    </div>
	<div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>Tabel Matriks Keputusan Ternormalisasi</h5>
            <div class="ibox-tools">
            </div>
        </div>
        <div class="ibox-content" style="overflow: auto;">
            <table class="table table-bordered">
                <thead class="">
        <tr>
            <th style="text-align:center;vertical-align:middle" rowspan="2" colspan="1" >
                Alternatif
            </th>
            <th style="text-align:center;" rowspan="1" colspan="<?=count($kriteria_list)?>">
                Kriteria
            </th>
        </tr>
        <tr>
			<?php foreach($kriteria_list as $key_kriteria=>$row_kriteria){?>
				<th style="text-align: center;"><?=$row_kriteria['KodeKriteria']?></th>
			<?php }?>
        </tr>
                </thead>
                <tbody>
                    <?php 
                        foreach($alternatif_list as $key_alternatif=>$row_alternatif){
                        $left   = $row_alternatif['KodeAlternatif'];
                        ?>
                        <tr>
                            <td><strong title="<?=$left?>"><?=$left?></strong></td>
                            <?php
                            foreach($kriteria_list as $key_kriteria=>$row_kriteria){
                                $right = $row_kriteria['KodeKriteria'];
								$bobot = $data_alternatif[$left][$right]['Value'];
								$data_alternatif[$left][$right]['Normalisasi'] = $bobot/$kriteria_code[$right]['BobotPembagi'];
                                ?>
                                <td style="text-align: center;"><?=number_format($data_alternatif[$left][$right]['Normalisasi'],4,'.','.')?></td>
                            <?php }?>
                        </tr>
                    <?php }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
	<div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>Tabel Matriks Keputusan Ternormalisasi Terbobot</h5>
            <div class="ibox-tools">
            </div>
        </div>
        <div class="ibox-content" style="overflow: auto;">
            <table class="table table-bordered">
                <thead class="">
        <tr>
            <th style="text-align:center;vertical-align:middle" rowspan="2" colspan="1" >
                Alternatif
            </th>
            <th style="text-align:center;" rowspan="1" colspan="<?=count($kriteria_list)?>">
                Kriteria
            </th>
        </tr>
        <tr>
			<?php
			foreach($kriteria_list as $key_kriteria=>$row_kriteria){?>
				<th style="text-align: center;"><?=$row_kriteria['KodeKriteria']?></th>
			<?php }?>
        </tr>
                </thead>
                <tbody>
                    <?php 
                        foreach($alternatif_list as $key_alternatif=>$row_alternatif){
                        $left   = $row_alternatif['KodeAlternatif'];
                        ?>
                        <tr>
                            <td><strong title="<?=$left?>"><?=$left?></strong></td>
                            <?php
                            foreach($kriteria_list as $key_kriteria=>$row_kriteria){
                                $right = $row_kriteria['KodeKriteria'];
								$bobot_kriteria = $data_kriteria[$right]['Value'];
								$data_alternatif[$left][$right]['NormalisasiTerbobot'] = $data_alternatif[$left][$right]['Normalisasi']*$bobot_kriteria;
								
								if($data_kriteria[$right]['KodeKriteria'] == $right)
								{
									$data_kriteria[$right]['ListNormalisasiTerbobot'][] = $data_alternatif[$left][$right]['NormalisasiTerbobot'];;
								}
                                ?>
                                <td style="text-align: center;"><?=number_format($data_alternatif[$left][$right]['NormalisasiTerbobot'],4,'.','.')?></td>
                            <?php }?>
                        </tr>
                    <?php }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
	<div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>Tabel Solusi Ideal Positif dan Negatif</h5>
            <div class="ibox-tools">
            </div>
        </div>
        <div class="ibox-content" style="overflow: auto;">
            <table class="table table-bordered">
                <thead class="">
        <tr>
            <th style="text-align:center;vertical-align:middle" rowspan="2" colspan="1" >
                Alternatif
            </th>
            <th style="text-align:center;" rowspan="1" colspan="<?=count($kriteria_list)?>">
                Kriteria
            </th>
        </tr>
        <tr>
			<?php foreach($kriteria_list as $key_kriteria=>$row_kriteria){?>
				<th style="text-align: center;"><?=$row_kriteria['KodeKriteria']?></th>
			<?php }?>
        </tr>
                </thead>
                <tbody>
                    <tr>
						<td>
							<strong>A+</strong>
						</td>
						<?php foreach($kriteria_list as $key_kriteria=>$row_kriteria){
							$right = $row_kriteria['KodeKriteria'];
							$atribut = $data_kriteria[$right]['Atribut'];
							$max_normalisasi_kriteria = max($data_kriteria[$right]['ListNormalisasiTerbobot']);
							$min_normalisasi_kriteria = min($data_kriteria[$right]['ListNormalisasiTerbobot']);
							if(strtolower($atribut) == 'benefit')
							{
								$data_kriteria[$right]['Aplus'] = $max_normalisasi_kriteria;
							}else{
								$data_kriteria[$right]['Aplus'] = $min_normalisasi_kriteria;
							}
							?>
							<td style="text-align: center;"><?=number_format($data_kriteria[$right]['Aplus'],6,'.','.')?></td>
						<?php }?>
					</tr>
                    <tr>
						<td>
							<strong>A-</strong>
						</td>
						<?php foreach($kriteria_list as $key_kriteria=>$row_kriteria){
							$right = $row_kriteria['KodeKriteria'];
							$atribut = $data_kriteria[$right]['Atribut'];
							$max_normalisasi_kriteria = max($data_kriteria[$right]['ListNormalisasiTerbobot']);
							$min_normalisasi_kriteria = min($data_kriteria[$right]['ListNormalisasiTerbobot']);
							if(strtolower($atribut) == 'benefit')
							{
								$data_kriteria[$right]['Aminus'] = $min_normalisasi_kriteria;
							}else{
								$data_kriteria[$right]['Aminus'] = $max_normalisasi_kriteria;
							}
							?>
							<td style="text-align: center;"><?=number_format($data_kriteria[$right]['Aminus'],6,'.','.')?></td>
						<?php }?>
					</tr>
                </tbody>
				<tfoot style="background-color: #F5F5F6;">
					<tr>
						<td style="text-align:center;">
							<strong>Atribut Kriteria</strong>
						</td>
						<?php foreach($kriteria_list as $key_kriteria=>$row_kriteria){
							$right = $row_kriteria['KodeKriteria'];
							$atribut = $data_kriteria[$right]['Atribut'];
							?>
							<td style="text-align: center;"><strong><?=$atribut?></strong></td>
						<?php }
						?>
					</tr>
				</tfoot>
            </table>
        </div>
    </div>
	<div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>Tabel Jarak Solusi Ideal Positif</h5>
            <div class="ibox-tools">
            </div>
        </div>
        <div class="ibox-content" style="overflow: auto;">
            <table class="table table-bordered">
                <thead class="">
        <tr>
            <th style="text-align:center;vertical-align:middle" rowspan="2" colspan="1" >
                Alternatif
            </th>
            <th style="text-align:center;" rowspan="1" colspan="<?=count($kriteria_list)?>">
                Kriteria
            </th>
            <th style="text-align:center;vertical-align:middle" rowspan="2" colspan="1" >
                SUM
            </th>
        </tr>
        <tr>
			<?php
			foreach($kriteria_list as $key_kriteria=>$row_kriteria){?>
				<th style="text-align: center;"><?=$row_kriteria['KodeKriteria']?></th>
			<?php }?>
        </tr>
                </thead>
                <tbody>
                    <?php 
					
                        foreach($alternatif_list as $key_alternatif=>$row_alternatif){
                        $left   = $row_alternatif['KodeAlternatif'];
                        ?>
                        <tr> 
                            <td><strong title="<?=$left?>"><?=$left?></strong></td>
                            <?php
                            foreach($kriteria_list as $key_kriteria=>$row_kriteria){
                                $right = $row_kriteria['KodeKriteria'];
								$bobot_kriteria = $data_kriteria[$right]['Value'];
								$data_alternatif[$left][$right]['NormalisasiTerbobotSolusiIdealPlus'] = ($data_alternatif[$left][$right]['NormalisasiTerbobot']-$data_kriteria[$right]['Aplus'])**2;
								if($sum_alternatif[$left]['KodeAlternatif'] == $left)
								{
									$sum_alternatif[$left]['SumJarakSolusiIdealPositif'] = $sum_alternatif[$left]['SumJarakSolusiIdealPositif']+ $data_alternatif[$left][$right]['NormalisasiTerbobotSolusiIdealPlus'];
								}
                                ?>
                                <td style="text-align: center;"><?=number_format($data_alternatif[$left][$right]['NormalisasiTerbobotSolusiIdealPlus'],6,'.','.')?></td>
                            <?php }?>
							<td style="text-align: center;">
							<?=number_format($sum_alternatif[$left]['SumJarakSolusiIdealPositif'],6,'.','.')?>
							</td>
                        </tr>
                    <?php }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
	<div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>Tabel Jarak Solusi Ideal Negatif</h5>
            <div class="ibox-tools">
            </div>
        </div>
        <div class="ibox-content" style="overflow: auto;">
            <table class="table table-bordered">
                <thead class="">
        <tr>
            <th style="text-align:center;vertical-align:middle" rowspan="2" colspan="1" >
                Alternatif
            </th>
            <th style="text-align:center;" rowspan="1" colspan="<?=count($kriteria_list)?>">
                Kriteria
            </th>
            <th style="text-align:center;vertical-align:middle" rowspan="2" colspan="1" >
                SUM
            </th>
        </tr>
        <tr>
			<?php
			foreach($kriteria_list as $key_kriteria=>$row_kriteria){?>
				<th style="text-align: center;"><?=$row_kriteria['KodeKriteria']?></th>
			<?php }?>
        </tr>
                </thead>
                <tbody>
                    <?php 
					
                        foreach($alternatif_list as $key_alternatif=>$row_alternatif){
                        $left   = $row_alternatif['KodeAlternatif'];
                        ?>
                        <tr>
                            <td><strong title="<?=$left?>"><?=$left?></strong></td>
                            <?php
                            foreach($kriteria_list as $key_kriteria=>$row_kriteria){
                                $right = $row_kriteria['KodeKriteria'];
								$bobot_kriteria = $data_kriteria[$right]['Value'];
								$data_alternatif[$left][$right]['NormalisasiTerbobotSolusiIdealMinus'] = ($data_alternatif[$left][$right]['NormalisasiTerbobot']-$data_kriteria[$right]['Aminus'])**2;
								if($sum_alternatif[$left]['KodeAlternatif'] == $left)
								{
									$sum_alternatif[$left]['SumJarakSolusiIdealNegatif'] = $sum_alternatif[$left]['SumJarakSolusiIdealNegatif']+ $data_alternatif[$left][$right]['NormalisasiTerbobotSolusiIdealMinus'];
								}
                                ?>
                                <td style="text-align: center;"><?=number_format($data_alternatif[$left][$right]['NormalisasiTerbobotSolusiIdealMinus'],4,'.','.')?></td>
                            <?php }?>
							<td style="text-align: center;">
							<?=number_format($sum_alternatif[$left]['SumJarakSolusiIdealNegatif'],4,'.','.')?>
							</td>
                        </tr>
                    <?php }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
	
	<div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>Jarak Antar nilai alternatif dengan Matriks Solusi Ideal Positif dan negatif</h5>
            <div class="ibox-tools">
            </div>
        </div>
        <div class="ibox-content" style="overflow: auto;">
            <table class="table table-bordered">
                <thead class="">
        <tr>
            <th style="text-align:center;vertical-align:middle" rowspan="1" colspan="1" >
                Alternatif
            </th>
            <th style="text-align:center;" rowspan="1" colspan="1">
                D+
            </th>
            <th style="text-align:center;" rowspan="1" colspan="1">
                D-
            </th>
        </tr>
                </thead>
                <tbody>
                    <?php 
						$array_preferensi = array();
                        foreach($alternatif_list as $key_alternatif=>$row_alternatif){
                        $left   = $row_alternatif['KodeAlternatif'];
						
						$sum_alternatif[$left]['JarakAntarNilaiPositif'] = sqrt($sum_alternatif[$left]['SumJarakSolusiIdealPositif']);
						$sum_alternatif[$left]['JarakAntarNilaiNegatif'] = sqrt($sum_alternatif[$left]['SumJarakSolusiIdealNegatif']);
						
						$sum_alternatif[$left]['NilaiPreferensi'] = $sum_alternatif[$left]['JarakAntarNilaiNegatif']/($sum_alternatif[$left]['JarakAntarNilaiNegatif']+$sum_alternatif[$left]['JarakAntarNilaiPositif']);
						$data_preferensi['KodeAlternatif'] = $left;
						$data_preferensi['NilaiPreferensi'] = $sum_alternatif[$left]['NilaiPreferensi'];
						$array_preferensi[$left]=$sum_alternatif[$left]['NilaiPreferensi'];
                        ?>
                        <tr>
                            <td><strong title="<?=$left?>"><?=$left?></strong></td>
                            <td style="text-align: center;">
							<?=number_format($sum_alternatif[$left]['JarakAntarNilaiPositif'],4,'.','.')?>
							</td>
                            <td style="text-align: center;">
							<?=number_format($sum_alternatif[$left]['JarakAntarNilaiNegatif'],4,'.','.')?>
							</td>
                        </tr>
                    <?php }
                    ?>
                </tbody>
            </table>
        </div>
    </div>	
	
	
	
	<div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>Nilai Preferensi Untuk Setiap Alternatif</h5>
            <div class="ibox-tools">
            </div>
        </div>
        <div class="ibox-content" style="overflow: auto;">
            <table class="table table-bordered">
                <thead class="">
        <tr>
            <th style="text-align:center;vertical-align:middle" rowspan="1" colspan="1" >
                Alternatif
            </th>
            <th style="text-align:center;" rowspan="1" colspan="1">
                Nilai
            </th>
            <th style="text-align:center;" rowspan="1" colspan="1">
                Ranking
            </th>
        </tr>
                </thead>
                <tbody>
                    <?php 
						$rank = array();
						$ordered_values = $array_preferensi;
						rsort($ordered_values);

						foreach ($array_preferensi as $keys => $value) {
							foreach ($ordered_values as $ordered_key => $ordered_value) {
								if ($value === $ordered_value) {
									$key = $ordered_key;
									break;
								}
							}
							$rank[$keys] = array('Ranking'=>(int) $key + 1);
						}
                        foreach($alternatif_list as $key_alternatif=>$row_alternatif){
                        $left   = $row_alternatif['KodeAlternatif'];
                        ?>
                        <tr>
                            <td><strong title="<?=$left?>"><?=$left?> - <?=$sum_alternatif[$left]['NamaAlternatif'];?></strong></td>
                            <td style="text-align: center;"><strong title=""><?=number_format($sum_alternatif[$left]['NilaiPreferensi'],4,'.','.')?></strong></td>
                            <td style="text-align: center;<?=($rank[$left]['Ranking']==1)?'font-weight: bold;':'';?>"><?=$rank[$left]['Ranking']?></td>
                        </tr>
                    <?php }
                    ?>
                </tbody>
            </table>
        </div>
    </div>