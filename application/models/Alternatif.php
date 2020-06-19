<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Alternatif extends MY_Model {
	protected $table = 'Alternatif';
	protected $primaryKey  = array('ProjectID','KodeAlternatif');
	// protected $fillable = array('Description','idCardStatus');
	public function __construct()
	{
		// If you use standard naming convention, this code can be omitted.
		/*$this->table = 'cars';
		$this->id_field = 'id';
		$this->row_type = 'Car_object';*/
		parent::__construct();
	}

	function get_last_auto_number($prefix)
	{
		$id_project = $this->session->userdata('ProjectID');
		$this->db->select('RIGHT(tbl."KodeAlternatif",3) "KodeAlternatif"',false);
		$this->db->order_by('tbl.KodeAlternatif DESC');
		$this->db->group_by('tbl.KodeAlternatif');
		
		$this->db->where("LEFT(tbl.\"KodeAlternatif\",1) = '$prefix'");
		$this->db->where("tbl.\"ProjectID\" = '$id_project'");
		
		$query = $this->db->get($this->table.' tbl','1','0');
		// echo $this->db->last_query();
		// exit;
		
		$no_last =  $query->row_array();
		$no_next = 1;
		if ($no_last)
		{
			$no_next = $no_last['KodeAlternatif']+1;
		}

		$no = $prefix.sprintf('%03s', $no_next);
	
		$query->free_result();
		
		return $no;
	}

	function getPairWise()
	{
		$id_project = $this->session->userdata('ProjectID');
		$qry = 'SELECT
		t1."KodeAlternatif" "Left",t1."NamaAlternatif" "LeftName", t2."KodeAlternatif" "Right",t2."NamaAlternatif" "RightName"
	FROM
		"Alternatif" t1 JOIN
		"Alternatif" t2 ON t1."KodeAlternatif" < t2."KodeAlternatif"
		WHERE t1."ProjectID" = '.$id_project.' AND t2."ProjectID" = '.$id_project.'
		ORDER BY t1."KodeAlternatif",t2."KodeAlternatif"';

		$query = $this->db->query($qry);
		// echo $this->db->last_query();
		return $query->result_array();
	}
}