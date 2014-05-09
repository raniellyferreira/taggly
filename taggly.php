<?php 

/**
 * Taggly
 *
 * This class generate tags cloud
 *
 * @category			Library
 * @author				Ranielly Ferreira
 * @based 				Taglly class of Derek Jones
 * @version				1.2.1
 * @last modfication	09/05/2014
 * @contact				raniellyferreira@rfs.net.br
 
 $configs = array(
	'min_font' => 16, //Minimum limit for font size
	'max_font' => 48, //Maximum limit for font size
	'size_type' => 'px', //Type size for setting in the css tags
	'full_tag_open' => '<div id="tag_cloud">', //Start of tag where will all the code
	'full_tag_close' => '</div>', //End of tag
	'item_tag_open' => '<span>', //Start tag for tags
	'item_tag_close' => '</span>',//End of itens tag
	'shuffle' => TRUE, //Random tags
	'link_tag_class' => 'my_css_class', //Class for links tags
	'links_target' => '_top', //Target of links tags
	'find_match' => array(), //Insert here the names of the tags to be highlighted.
	'match_class' => 'match_class' //Class for tags highlighted
);

 
 
 */
 
class Taggly 
{
	public $min_font 			= 16;
    public $max_font 			= 48;
	public $size_type			= 'px';
    public $full_tag_open		= '<div id="tag_cloud">';
    public $full_tag_close 		= '</div>';
	public $item_tag_open		= '<span>';
	public $item_tag_close		= '</span>';
    public $shuffle 			= TRUE;
	public $shuffle_font_size	= 0;
    public $link_tag_class		= 'my_css_class';
	public $links_target		= '_top';
    public $find_match 			= array();
    public $match_class 		= 'match_class';
	public $max_rating			= 0;
	public $scale_precision		= TRUE;
	public static $ignored_words= array('?','of','the','is','off','you','them','then','at','with','i','it','we','de','e');
	
	private $tags_content 		= array();
	private $mid_rating			= 0;
	
	
    function __construct($array = array())
    {
		$this->load($array);
	}
	
	public function load($array = array())
	{
		if((bool) ! $array)
		{
			return FALSE;
		}
		
		foreach($array as $k => $v)
		{
			if(isset($this->$k))
			{
				$this->$k = $v;
			}
		}
		
		return $this;
	}
	
	public function add_tags($tags)
	{
		if(is_scalar($tags)) return FALSE;
		
		if($this->_check_type_array($tags))
		{
			foreach($tags as $tag)
			{
				if(is_array($tag))
				{
					$this->add_tag($tag);
				}
			}
		} else $this->add_tag($tags);
		
		return $this;
	}
	
	public function add_tag($tag)
	{
		if((bool) ! $tag OR ! is_array($tag)) return FALSE;
		
		$this->tags_content[] = $tag;
		
		return $this;
	}
	
	public function cloud($data = array(),$options = array())
	{
		$this->load($options);
		
		$data = ((bool) $data) ? $data : $this->tags_content;
		
		if((bool) ! $data) return FALSE;
		
		$this->_get_max_rating();
		
		if($this->shuffle) shuffle($data);
		
		$html = NULL;
		$html .= $this->full_tag_open;
		
		foreach($data as $v)
		{
			
			if( ! isset($v[1]) OR (bool) ! $v) continue;
			
			$html .= $this->item_tag_open;
			$html .= '<a class="';
			$html .= $this->link_tag_class;
			$html .= '" style="font-size: ';
			
			if($this->shuffle_font_size > 0 AND $v[0] < $this->shuffle_font_size)
			{
				$html .= rand($this->min_font,$this->max_font);
			} else
			{
				$html .= $this->_calc_rating($v[0]);
			}
			
			$html .= $this->size_type;
			$html .= '"';
			
			
			if(isset($v[2]))
			{
				$html .= ' target="';
				$html .= $this->links_target;
				$html .= '"';
				$html .= ' href="';
				$html .= $v[2];
				$html .= '"';
			}
			
			$html .= '>';
			
			if( ! in_array($v[1],$this->find_match) )
			{
				$html .= $v[1];
			} else
			{
				$html .= '<span class="'.$this->match_class.'">';
				$html .= $v[1];
				$html .= '</span>';
			}
			
			$html .= '</a>';
			
			$html .= $this->item_tag_close.' ';
		}
		
		$html .= $this->full_tag_close;
		
		return $html;
	}
	
	private function _get_max_rating($data = array())
	{
		$data = ((bool) $data) ? $data : $this->tags_content;
		
		if(empty($data)) return FALSE;
		
		foreach($this->tags_content as $tag)
		{
			if(isset($tag[0]) AND $tag[0] > $this->max_rating)
			{
				$this->max_rating = $tag[0];
			}
		}
		
		if($this->max_rating == 0) return false;
		
		if($this->scale_precision)
		$this->mid_rating = $this->max_font / $this->max_rating;
		else
		$this->mid_rating = $this->max_rating / $this->max_font;
	}
	
	private function _calc_rating($rat)
	{
		$res = ceil($rat * $this->mid_rating);
		if($res >= $this->min_font AND $res <= $this->max_font)
		return $res;
		elseif($res < $this->min_font)
		return $this->min_font;
		else
		return $this->max_font;
	}
	
	private function _check_type_array($array)
	{
		foreach($array as $row)
		{
			if(!is_scalar($row))
			return TRUE;
			else
			return FALSE;
		}
	}
	
	public function clean()
	{
		$configs = array(
			'min_font' => 16,
			'max_font' => 48,
			'size_type' => 'px',
			'full_tag_open' => '<div id="tag_cloud">',
			'full_tag_close' => '</div>',
			'item_tag_open' => '<span>',
			'item_tag_close' => '</span>',
			'shuffle' => TRUE, //Random tags
			'link_tag_class' => 'my_css_class',
			'links_target' => '_top',
			'find_match' => array(),
			'match_class' => 'match_class'
		);
		
		$this->load($configs);
		
		$this->tags_content = array();
		
		return $this;
	}
}
