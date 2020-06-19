<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class BobotSubKriteria extends MY_Model {
	protected $table = 'BobotSubKriteria';
	protected $primaryKey  = array('ProjectID','KodeKriteria');
	// protected $fillable = array('Description','idCardStatus');
	public function __construct()
	{
		// If you use standard naming convention, this code can be omitted.
		/*$this->table = 'cars';
		$this->id_field = 'id';
		$this->row_type = 'Car_object';*/
		parent::__construct();
	}
}