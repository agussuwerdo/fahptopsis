<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FuzzyMaster extends MY_Model {
	protected $table = 'FuzzyMaster';
	protected $primaryKey  = array('FuzzyID');
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