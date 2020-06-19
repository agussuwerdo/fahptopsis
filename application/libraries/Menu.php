<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menu
{
	private $CI = '';

	function __construct()
    {
        $this->CI =& get_instance();
    }
	
	function get_menu($parent_id,$level=0)
	{
		if($level>1000)
		{
			die('ERROR UNLIMITED LOOP');
		}
		$base_url = base_url();
		$menu = '';
		$this->CI->db->where('am.fidMenu = '.$parent_id.'');
		$this->CI->db->where('am.isActive = 1');
		$this->CI->db->where('am.menuType = 1');
		$this->CI->db->where('am."idMenu" IN 
									(SELECT "fidMenu"
											FROM "msOperatorAccess"
											WHERE "fidOperatorType" = '.$this->CI->session->userdata('fidOperatorType').')');
		$this->CI->db->order_by('orderBy','ASC');
		$get_menu = $this->CI->db->get('Menu am');
		// echo $this->CI->db->last_query().';<br>';
		$menu_list = $get_menu->result_array();
		
		$level = $level+1;
		
		foreach($menu_list as $key=>$row)
		{
			$this->CI->db->where('am.fidMenu = '.$row['idMenu'].'');
			$this->CI->db->where('am.isActive = 1');
			$this->CI->db->where('am.menuType = 1');
			$this->CI->db->where('am."idMenu" IN 
									(SELECT "fidMenu"
											FROM "msOperatorAccess"
											WHERE "fidOperatorType" = '.$this->CI->session->userdata('fidOperatorType').')');
			$this->CI->db->order_by('orderBy','ASC');
			$check_child = $this->CI->db->get('Menu am');
			$child_count = 0;
			$child_count = $check_child->num_rows();
			$span_arrow = '';
			
			$ul_class = 'dropdown nav-item';
			
			$title = $row['title'];
			$icon = $row['icon']?'<i class="'.$row['icon'].'"></i>':'';
			
			
			if($level == 0)
			{
				$title = '<span class="nav-label">'.$row['title'].'</span>';
			}
			
			if($level == 1)
			{
				$title = '<span>'.$row['title'].'</span>';
				$ul_class = 'class="nav nav-second-level collapse"';
				
			}
			
			if($level == 2)
			{
				$title = $row['title'];
				$ul_class = 'class="nav nav-third-level collapse"';
				
			}
			if($level == 3)
			{
				$title = $row['title'];
				$ul_class = '';
				
			}
			if($level >= 4)
			{
				$title = $row['title'];
				$li_class = '';
				$ul_class = 'dropdown';
				
			}
			
			if($level > 1)
			{
				$ul_class .= 'child';
			}
			
			if($child_count>0)
			{
				$ul_class .= '';
				$span_arrow = '<span class="fa arrow"></span>';
			}
			
			$href = $row['url']?'href="'.$base_url.$row['url'].'"':'';
			$menu  .= '<input style="display: none;" class="hidden" id="title_'.preg_replace('/[^a-z0-9]/i', '_', ($row['url'])).'" value="'.$row['title'].'">';
			$menu  .= '<input style="display: none;" class="hidden" id="description_'.preg_replace('/[^a-z0-9]/i', '_', ($row['url'])).'" value="'.$row['description'].'">';
			$menu  .= '<li id="'.preg_replace('/[^a-z0-9]/i', '_', ($row['url'])).'">';
			$menu  .= '
				<a href="'.$base_url.$row['url'].'">
					'.$icon.'
					'.$title.'
					'.$span_arrow.'
				</a>
			';
			if($child_count>0)
			{
				$menu .= '<ul '.$ul_class.'>';
			}
			$menu .= $this->get_menu($row['idMenu'],$level);
			if($child_count>0)
			{
				$menu .= '</ul>';
			}
			$menu .='</li>';
		}
		return $menu;
	}
	
	function get_menu_privilege($parent_id,$level=0,$idOperatorType)
	{
		if($level>1000)
		{
			die('ERROR UNLIMITED LOOP');
		}
		$base_url = base_url();
		$menu = '';
		$this->CI->db->where('am.fidMenu = '.$parent_id.'');
		$this->CI->db->where('am.isActive = 1');
		$this->CI->db->join('(SELECT * FROM "msOperatorAccess" WHERE  "fidOperatorType" = '.$idOperatorType.') mas','am."idMenu" = mas."fidMenu"','left');
		$this->CI->db->order_by('orderBy','ASC');
		$get_menu = $this->CI->db->get('Menu am');
		// echo $this->CI->db->last_query().';<br>';
		$menu_list = $get_menu->result_array();
		
		$level = $level+1;
		
		foreach($menu_list as $key=>$row)
		{
			$this->CI->db->where('am.fidMenu = '.$row['idMenu'].'');
			$this->CI->db->where('am.isActive = 1');
			$this->CI->db->join('(SELECT * FROM "msOperatorAccess" WHERE  "fidOperatorType" = '.$idOperatorType.') mas','am."idMenu" = mas."fidMenu"','left');
			$this->CI->db->order_by('orderBy','ASC');
			$check_child = $this->CI->db->get('Menu am');
			// echo $this->CI->db->last_query();
			$child_count = 0;
			$child_count = $check_child->num_rows();
			$span_arrow = '';
			
			$ul_class = 'dropdown nav-item';
			
			$title = $row['title'];
			$icon = $row['icon']?'<i class="'.$row['icon'].'"></i>':'';
			
				$checked = '';
			if($row['idOperatorAccess'])
			{
				$checked = 'class="jstree-clicked"';
			}
			
			$menu  .= '<ul class="jstree-checked">';
			$menu  .= '<li class="jstree-open" name="selected" id="chek_'.$row['idMenu'].'"  value="'.$row['idMenu'].'"><a name="selected2" id="_chek2'.$row['idMenu'].'" '.$checked.' >'.$title.'</a>';
			if($child_count>0)
			{
			}
			$menu .= $this->get_menu_privilege($row['idMenu'],$level,$idOperatorType);
			if($child_count>0)
			{
			}
			$menu .= '</li>';
			$menu .='</ul>';
		}
		return $menu;
	}
	
	function generate()
	{
		echo $this->get_menu(0,0);
	}
	
}