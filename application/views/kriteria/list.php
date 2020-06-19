<table class="table table-bordered">
    <thead>
    <tr>
        <th>#</th>
        <th>Kode</th>
        <th>Nama Kriteria</th>
        <th>Atribut</th>
        <th>Aksi</th>
    </tr>
    </thead>
    <tbody>
        <?php $no=1; foreach($list_data['result_array'] as $row){
            if($row['KriteriaLevel'] == 0)
            {
            ?>
            <tr>
                <td><?=$no++;?></td>
                <td><?=$row['KodeKriteria']?></td>
                <td><?=$row['NamaKriteria']?></td>
                <td><?=$row['Atribut']?></td>
                <td>
                    <button type="button" onclick="removeKriteria(<?=quotedStr($row['KodeKriteria'])?>)" class="btn pull-right btn-xs btn-outline btn-danger"><i class="fa fa-trash"></i></button>
                    <button type="button" onclick="modalView('listKriteria/addSubKriteria/<?=$row['KodeKriteria']?>','Tambah Data','','lg')" class="btn btn-xs btn-outline btn-info">Tambah Sub Kriteria</button>
                    <button type="button" onclick="modalView('listKriteria/addKriteria/<?=$row['KodeKriteria']?>','Ubah Data','','lg')" class="btn pull-right btn-xs btn-outline btn-primary"><i class="fa fa-pencil"></i></button>
                </td>
            </tr>
            <?php }?>
            <?php $nodetail=1; foreach($list_data['result_array'] as $rowdetail){
                if(($rowdetail['KodeKriteriaParent'] == $row['KodeKriteria']) && $rowdetail['KriteriaLevel'] == 1)
                {   
                    ?>
                        <tr style="font-size:9px">
                            <td></td>
                            <td><?=$rowdetail['KodeKriteria']?></td>
                            <td><?=$rowdetail['NamaKriteria']?></td>
                            <td><?=$rowdetail['Atribut']?></td>
                            <td>
                            <button type="button" onclick="removeKriteria(<?=quotedStr($rowdetail['KodeKriteria'])?>)" class="btn pull-right btn-xs btn-outline btn-danger"><i class="fa fa-trash"></i></button>
                            <button type="button" onclick="modalView('listKriteria/addSubKriteria/<?=$rowdetail['KodeKriteriaParent']?>/<?=$rowdetail['KodeKriteria']?>','Ubah Data','','lg')" class="btn pull-right btn-xs btn-outline btn-info"><i class="fa fa-pencil"></i></button>
                            </td>
                        </tr>
                    <?php 
                } 
            }?>
        <?php 
    
    }?>
    </tbody>
</table>