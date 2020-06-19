<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kriteria extends MY_Model {
	protected $table = 'Kriteria';
	protected $primaryKey  = array('ProjectID','KodeKriteria');
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
		$this->db->select('RIGHT(tbl."KodeKriteria",2) "KodeKriteria"',false);
		$this->db->order_by('tbl.KodeKriteria DESC');
		$this->db->group_by('tbl.KodeKriteria');
		
		$this->db->where("LEFT(tbl.\"KodeKriteria\",2) = '$prefix'");
		$this->db->where("tbl.\"ProjectID\" = '$id_project'");
		
		$query = $this->db->get($this->table.' tbl','1','0');
		// echo $this->db->last_query();
		// exit;
		
		$no_last =  $query->row_array();
		$no_next = 1;
		if ($no_last)
		{
			$no_next = $no_last['KodeKriteria']+1;
		}

		$no = $prefix.sprintf('%02s', $no_next);
	
		$query->free_result();
		
		return $no;
	}

	function getPairWise()
	{
		$id_project = $this->session->userdata('ProjectID');
		$qry = 'SELECT
		t1."KodeKriteria" "Left",t1."NamaKriteria" "LeftName", t2."KodeKriteria" "Right",t2."NamaKriteria" "RightName"
	FROM
		"Kriteria" t1 JOIN
		"Kriteria" t2 ON t1."KodeKriteria" < t2."KodeKriteria"
		WHERE t1."KriteriaLevel" = 1 AND t2."KriteriaLevel" = 1
		AND t1."ProjectID" = '.$id_project.' AND t2."ProjectID" = '.$id_project.'
		ORDER BY t1."KodeKriteria",t2."KodeKriteria"';

		$query = $this->db->query($qry);

		return $query->result_array();
	}
}