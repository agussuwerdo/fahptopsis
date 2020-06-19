<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PairWiseAlternatif extends MY_Model {
	protected $table = 'PairWiseAlternatif';
	protected $primaryKey  = array('FuzzyID','FuzzyID','Left','Right');
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