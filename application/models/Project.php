<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Project extends MY_Model {
	protected $table = 'Project';
	protected $primaryKey  = array('ProjectID');
	// protected $fillable = array('Description','idCardStatus');
	public function __construct()
	{
		// If you use standard naming convention, this code can be omitted.
		/*$this->table = 'cars';
		$this->id_field = 'id';
		$this->row_type = 'Car_object';*/
		parent::__construct();
	}
	
	function update_count_project($id_project)
	{
		
		$qry = 'UPDATE "public"."Project" 
SET "CountKriteria" = ( SELECT COUNT ( * ) FROM "Kriteria" WHERE "ProjectID" = '.$id_project.' AND "KriteriaLevel" = 0 ),
"CountAlternatif" = ( SELECT COUNT ( * ) FROM "Alternatif" WHERE "ProjectID" = '.$id_project.' ) 
WHERE
	"ProjectID" = '.$id_project.';';
				$query = $this->db->query($qry);
				// echo $this->db->last_query();
	}
}