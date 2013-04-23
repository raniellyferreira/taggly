<?php 

/**
 * Taggly
 *
 * This class generate tags cloud
 *
 * @category			Library
 * @author				Ranielly Ferreira
 * @based 				Taglly class of Derek Jones
 * @version				1.0.0
 * @last modfication	23/04/2013
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
	public $min_font 		= 16;
    public $max_font 		= 48;
	public $size_type		= 'px';
    public $full_tag_open	= '<div id="tag_cloud">';
    public $full_tag_close 	= '</div>';
	public $item_tag_open	= '<span>';
	public $item_tag_close	= '</span>';
    public $shuffle 		= TRUE;
    public $link_tag_class	= 'my_css_class';
	public $links_target	= '_top';
    public $find_match 		= array();
    public $match_class 	= 'match_class';
	
	private $tags_content 	= array();
	
	
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
			
			if($v[0] <= $this->max_font AND $v[0] >= $this->min_font)
			{
				$html .= $v[0];
			}else
			{
				if($v[0] > $this->max_font)
				{
					$html .= $this->max_font;
				} else
				{
					$html .= $this->min_font;
				}
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
}
