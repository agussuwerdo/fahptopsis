<div class="ibox-title">
    <h2>Bobot Alternatif Kriteria <?=$title?></h2>
</div>
<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>Tabel Matriks</h5>
            <div class="ibox-tools">
            </div>
        </div>
        <div class="ibox-content" style="overflow: auto;">
            <table class="table table-bordered ">
                <thead class="">
                <tr>
                    <th>Alternatif</th>
                    <?php foreach($alternatif_list as $key_alternatif=>$row_alternatif){?>
                        <th colspan="3" style="text-align: center;"><?=$row_alternatif['KodeAlternatif']?></th>
                    <?php }?>
                </tr>
                </thead>
                <tbody>
                    <?php 
                        $sum_matriks = array();
                        foreach($alternatif_list as $key_alternatif=>$row_alternatif){
                        $left   = $row_alternatif['KodeAlternatif'];
                        ?>
                        <tr>
                            <td><strong title="<?=$left?>"><?=$left?></strong></td>
                            <?php
                            foreach($alternatif_list as $key_alternatif_up=>$row_alternatif_up){
                                $up = $row_alternatif_up['KodeAlternatif'];
                                $value_upper = isset($alternatif_data[$left][$up])?$alternatif_data[$left][$up]['U']:0;
                                $value_middle = isset($alternatif_data[$left][$up])?$alternatif_data[$left][$up]['M']:0;
                                $value_lower = isset($alternatif_data[$left][$up])?$alternatif_data[$left][$up]['L']:0;
                                $sum_matriks[$key_alternatif][$key_alternatif_up]['KodeAlternatif'] = $left;
                                $sum_matriks[$key_alternatif][$key_alternatif_up]['L'] = $value_lower;
                                $sum_matriks[$key_alternatif][$key_alternatif_up]['M'] = $value_middle;
                                $sum_matriks[$key_alternatif][$key_alternatif_up]['U'] = $value_upper;
								if($cr_table[$up]['Kode'] == $up)
								{
									$cr_table[$up]['SumM'] = $cr_table[$up]['SumM'] + $value_middle;
								}
                                ?>
                                <td style="text-align: center;<?=($up==$left)?'background-color: #dedede;':''?>"><?=number_format($value_lower,4,'.','.')?></td>
                                <td style="text-align: center;<?=($up==$left)?'background-color: #dedede;':''?>"><?=number_format($value_middle,4,'.','.')?></td>
                                <td style="text-align: center;<?=($up==$left)?'background-color: #dedede;':''?>"><?=number_format($value_upper,4,'.','.')?></td>
                            <?php }?>
                        </tr>
                    <?php }
                    // buat tabel summary
                    foreach($sum_matriks as $sum_key=>$sum_row){
                        foreach($sum_row as $key=>$row)
                        {
                            if($alternatif_code[$row['KodeAlternatif']]['Kode'] == $row['KodeAlternatif'])
                            {
                                $alternatif_code[$row['KodeAlternatif']]['L'] = $alternatif_code[$row['KodeAlternatif']]['L']+$row['L'];
                                $alternatif_code[$row['KodeAlternatif']]['M'] = $alternatif_code[$row['KodeAlternatif']]['M']+$row['M'];
                                $alternatif_code[$row['KodeAlternatif']]['U'] = $alternatif_code[$row['KodeAlternatif']]['U']+$row['U'];
                            }
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>Fuzzy Sum</h5>
            <div class="ibox-tools">
            </div>
        </div>
        <div class="ibox-content" style="overflow: auto;">
            <table class="table table-bordered "> 
                <thead class="">
                <tr>
                    <th>NO</th>
                    <th>Alternatif</th>
                    <th style="text-align: center;">LOW</th>
                    <th style="text-align: center;">MIDDLE</th>
                    <th style="text-align: center;">UPPER</th>
                </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    $sum_l = 0;
                    $sum_m = 0;
                    $sum_u = 0;
                    foreach($alternatif_code as $key=>$row)
                    {
                        $sum_l = $sum_l+$row['L'];
                        $sum_m = $sum_m+$row['M'];
                        $sum_u = $sum_u+$row['U'];
                        ?>
                    <tr>
                        <td><strong title=""><?=$no++;?></strong></td>
                        <td><strong title=""><?=$row['Kode']?></strong></td>
                        <td style="text-align: center;"><?=number_format($row['L'],4,'.','.')?></td>
                        <td style="text-align: center;"><?=number_format($row['M'],4,'.','.')?></td>
                        <td style="text-align: center;"><?=number_format($row['U'],4,'.','.')?></td>
                    </tr>
                    <?php
                    }
                    ?>
                    <tr>
                        <td style="text-align: center;" colspan="2"><strong>SUM</strong></td>
                        <td style="text-align: center;"><?=number_format($sum_l,4,'.','.')?></td>
                        <td style="text-align: center;"><?=number_format($sum_m,4,'.','.')?></td>
                        <td style="text-align: center;"><?=number_format($sum_u,4,'.','.')?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>Fuzzy Syntethic Extent Matriks</h5>
            <div class="ibox-tools">
            </div>
        </div>
        <div class="ibox-content" style="overflow: auto;">
            <table class="table table-bordered "> 
                <thead class="">
                <tr>
                    <th>NO</th>
                    <th>Alternatif</th>
                    <th style="text-align: center;">LOW</th>
                    <th style="text-align: center;">MIDDLE</th>
                    <th style="text-align: center;">UPPER</th>
                </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    $fuzzy_extent = array();
                    foreach($alternatif_code as $key=>$row)
                    {
                        $data['Kode'] = $row['Kode'];
                        $data['L']    = $row['L']/$sum_u;
                        $data['M']    = $row['M']/$sum_m;
                        $data['U']    = $row['U']/$sum_l;
                        $fuzzy_extent[] = $data;
                        ?>
                    <tr>
                        <td><strong title=""><?=$no++;?></strong></td>
                        <td><strong title=""><?=$row['Kode']?></strong></td>
                        <td style="text-align: center;"><?=number_format(($row['L']/$sum_u),4,'.','.')?></td>
                        <td style="text-align: center;"><?=number_format(($row['M']/$sum_m),4,'.','.')?></td>
                        <td style="text-align: center;"><?=number_format(($row['U']/$sum_l),4,'.','.')?></td>
                    </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>Mi > Mj</h5>
            <div class="ibox-tools">
               
            </div>
        </div>
        <div class="ibox-content" style="overflow: auto;">
            <table class="table table-bordered ">
                <thead class="">
                <tr>
                    <th>Alternatif</th>
                    <?php foreach($alternatif_list as $key_alternatif=>$row_alternatif){?>
                        <th style="text-align: center;"><?=$row_alternatif['KodeAlternatif']?></th>
                    <?php }?>
                </tr>
                </thead>
                <tbody>
                    <?php 
                        $min_kemungkinan = array();
                        $index_vertical = 0;
                        foreach($alternatif_list as $key_alternatif=>$row_alternatif){
                        $left   = $row_alternatif['KodeAlternatif'];
                        ?>
                        <tr>
                            <td><strong title="<?=$left?>"><?=$left?></strong></td>
                            <?php
                            $index = 0;
                            $datas=array();
                            foreach($alternatif_list as $key_alternatif_up=>$row_alternatif_up){
                                $up = $row_alternatif_up['KodeAlternatif'];
                                $m1 = 0;
                                $m2 = 0;
                                $l1 = 0;
                                $u2 = 0;
                                $value = '0';
                                $min = 1;
								if($cr_table[$up]['Kode'] == $up)
								{
									$alternatif_data[$left][$up]['CrNormalize'] = $sum_matriks[$key_alternatif][$key_alternatif_up]['M'] / $cr_table[$up]['SumM'] ;
								}
                                if($up!=$left)
                                {
                                    if(isset($fuzzy_data[$left][$up]))
                                    {
                                        $m1 = $fuzzy_extent[$index_vertical]['M'];
                                        $m2 = $fuzzy_extent[$index]['M'];
                                        $l1 = $fuzzy_extent[$index]['L'];
                                        $u2 = $fuzzy_extent[$index_vertical]['U'];
                                        if($m1>$m2)
                                        {
                                            $value = 1;
                                        }else if($l1>$u2){
                                            $value = 0;
                                        }else{
                                            $value = ($l1-$u2)/(($m1-$u2)-($m2-$l1));
                                        }
                                    }else{
                                        $m1 = $fuzzy_extent[$index_vertical]['M'];
                                        $m2 = $fuzzy_extent[$index]['M'];
                                        $l1 = $fuzzy_extent[$index]['L'];
                                        $u2 = $fuzzy_extent[$index_vertical]['U'];
                                        if($m1>$m2)
                                        {
                                            $value = 1;
                                        }else if($l1>$u2){
                                            $value = 0;
                                        }else{
                                            $value = ($l1-$u2)/(($m1-$u2)-($m2-$l1));
                                        }
                                    }
                                }
                                ?>
                                <td style="text-align: center;<?=($up==$left)?'background-color: #dedede;':''?>"><?=number_format(($value?:0),4,'.','.')?></td>
                            <?php
                                $index++;
                                $datas['KodeAlternatif'] = $row_alternatif['KodeAlternatif'];
                                if($value)
                                $datas['Value'][] = $value;
                                $min_kemungkinan[$left] = $datas;
                            }?>
                        </tr>
                    <?php 
                    // $min_kemungkinan[] =  $datas[$index];
                        $index_vertical++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php // hitung consistensy
		foreach($alternatif_list as $key_alternatif=>$row_alternatif){
		$left   = $row_alternatif['KodeAlternatif'];
			foreach($alternatif_list as $key_alternatif_up=>$row_alternatif_up){
				$up = $row_alternatif_up['KodeAlternatif'];
				
			}
				if($cr_table[$left]['Kode'] == $left)
				{
					foreach($alternatif_data[$left] as $daks)
					{
						$cr_table[$left]['SumNormalize'] = $cr_table[$left]['SumNormalize']+ $daks['CrNormalize'];
					}
				}
				$cr_table[$left]['VektorPrioritas'] = $cr_table[$left]['SumNormalize'] / count($cr_table);
				// print_r($cr_table[$left]['VektorPrioritas']);
				// echo "<hr>";
				// 
				if($cr_table[$left]['Kode'] == $left)
				{
					foreach($alternatif_data[$left] as $daks2)
					{
						$cr_table[$left]['BobotKriteria'] = $cr_table[$left]['BobotKriteria'] + ($daks2['M'] * $cr_table[$left]['VektorPrioritas']);
					}
				}
		}
		$consistensy_index = 0;
		$consistensy_ratio = 0;
		$random_indeks = $get_random_indeks['RatioIndex']?:0;
		$sum_kriteria_normalisasi = 0;
		$avg_kriteria_normalisasi = 0;
		foreach($alternatif_list as $key_alternatif=>$row_alternatif){
		$left   = $row_alternatif['KodeAlternatif'];
			foreach($alternatif_list as $key_alternatif_up=>$row_alternatif_up){
				$up = $row_alternatif_up['KodeAlternatif'];
			}
			$cr_table[$left]['BobotKriteriaNormalisasi'] = $cr_table[$left]['BobotKriteria'] /  $cr_table[$left]['VektorPrioritas'];
			$sum_kriteria_normalisasi = $sum_kriteria_normalisasi + $cr_table[$left]['BobotKriteriaNormalisasi'];
		}
		$avg_kriteria_normalisasi = $sum_kriteria_normalisasi / count($cr_table);
		$consistensy_index = ($avg_kriteria_normalisasi-count($cr_table))/(count($cr_table)-1);
		$consistensy_ratio = (($consistensy_index>0)&&($random_indeks>0))?($consistensy_index / $random_indeks):0;
	// buat tabel summary
	foreach($sum_matriks as $sum_key=>$sum_row){
		foreach($sum_row as $key=>$row)
		{
			if($alternatif_code[$row['KodeAlternatif']]['Kode'] == $row['KodeAlternatif'])
			{
				$alternatif_code[$row['KodeAlternatif']]['L'] = $alternatif_code[$row['KodeAlternatif']]['L']+$row['L'];
				$alternatif_code[$row['KodeAlternatif']]['M'] = $alternatif_code[$row['KodeAlternatif']]['M']+$row['M'];
				$alternatif_code[$row['KodeAlternatif']]['U'] = $alternatif_code[$row['KodeAlternatif']]['U']+$row['U'];
			}
		}
	}
?>
<div class="col-lg-12">
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>Bobot Akhir <?=$title?></h5>
            <div class="ibox-tools">
               
            </div>
        </div>
        <div class="ibox-content" style="overflow: auto;">
            <table class="table table-bordered ">
                <thead class="">
                <tr>
                    <th>Alternatif</th>
                    <th>Nilai Minimum</th>
                    <th>Bobot Prioritas Alternatif</th>
                </tr>
                </thead>
                <tbody>
                    <?php
                        $sum_min_value = 0;
                        foreach($min_kemungkinan as $key_kemungkinan=>$row_kemungkinan){
							$min_value = isset($row_kemungkinan['Value'])?min($row_kemungkinan['Value']):0;
                            $sum_min_value = $sum_min_value + $min_value;
                        }
                        $min_value = 0;
                        $normalisasi = 0;
                        $sum_normalisasi = 0;
                        foreach($min_kemungkinan as $key_kemungkinan=>$row_kemungkinan){
							$get_alternatif_name = $alternatif_code[$row_kemungkinan['KodeAlternatif']];
							$min_value = isset($row_kemungkinan['Value'])?min($row_kemungkinan['Value']):0;
                            $normalisasi = $min_value/$sum_min_value;
                            $sum_normalisasi = $normalisasi + $sum_normalisasi;
                            ?>
                            <tr>
                                <td><strong title=""><?=$row_kemungkinan['KodeAlternatif']?> - <?=$get_alternatif_name['NamaAlternatif']?></strong></td>
                                <td style="text-align: center;"><?=number_format($min_value,4,'.','.')?></td>
                                <td style="text-align: center;"><input name="bobot_alternatif[<?=$kode_kriteria?>][<?=$row_kemungkinan['KodeAlternatif']?>]" style="border:none;text-align:center"  type="number" readonly value="<?=number_format($normalisasi,4,'.','.')?>"></td>
                            </tr>
                        <?php 
                        }
                    ?>
                </tbody>
                <tr>
                    <td style="text-align: center;"><strong>SUM</strong></td>
                    <td style="text-align: center;"><?=number_format($sum_min_value,4,'.','.')?></td>
                    <td style="text-align: center;"><?=number_format($sum_normalisasi,4,'.','.')?></td>
                </tr>
            </table>
					<span>Consistency Index(Ci) = <?=number_format($consistensy_index,4,'.','.');?> </span>
					<br><span>Consistency Ratio(Cr) = <?=number_format($consistensy_ratio,4,'.','.');?> </span>
					<br><span>Cr < 0.1 = <?=($consistensy_ratio < 0.1)?'<strong>Konsisten</strong>':'<strong>Tidak Konsisten</strong>';?> </span>
        </div>
    </div>
</div>