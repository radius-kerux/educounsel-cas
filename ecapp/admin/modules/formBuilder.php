<?php

/**
 * 
 * @author Raymond
 *
 */

class formBuilder
{
	public $type	=	'text';
	public $size	=	25;
	public $tag		=	'input';
	public $label;	
	
	//----------------------------------------------------------------------------------------
	//HTML Attributes Properties
	
	public $name;	//this is required
	public $id;
	public $align	=	'left'; //for legend
	
	//----------------------------------------------------------------------------------------
	//Bool
	
	public $disabled	=	FALSE;
	public $selected	=	FALSE;
	public $checked		=	FALSE;
	public $readonly	=	FALSE;
	public $multiple	=	FALSE;
		
	//----------------------------------------------------------------------------------------
	
	public $action;
	public $method;
	public $legend;
	public $text;
	public $content; 
	
	//----------------------------------------------------------------------------------------
	
	private  $global_attributes	=	array (
		'id', 'class', 'style', 'tabindex', 'accesskey'
	);
	
	//----------------------------------------------------------------------------------------
	
	private function __construct()
	{
	}
	
	//----------------------------------------------------------------------------------------
	
	public function __set ( $properties, $value )
	{
		$this->$properties	=	$value;
	}
	
	//----------------------------------------------------------------------------------------
	
	public function buildForm ()
	{
		echo '<pre>';
		foreach ( $this as $index => $value )
		var_dump($index);
		die;
	}
	
	//----------------------------------------------------------------------------------------
	
	public static function build ()
	{
		return	new self ();
	}
	
	//----------------------------------------------------------------------------------------
	
	public function element ()
	{
		switch ( $this->tag ) 
		{
			case 'input':
				return $this->generateInput ();
			break;
			
			case 'textarea':
				return $this->generateTextArea () ;
			break;
			
			case 'select':
				return $this->generateSelect ( $this->content );
			break;
			
			case 'option':
				return $this->generateOption( $this->text );
			break;
				
			case 'button':
				return $this->generateButton ( $this->text );
			break;
			
			case 'fieldset':
				return $this->generateFieldSet ( $this->content );
			break;
			
			case 'optgroup':
				return $this->generateOptionGroup ( $this->content );
			break;
			
			case 'datalist':
				return $this->generateDatalist ( $this->content );
			break;
			
			default:
				return $this->generateInput ();
			break;
		}
	}
	
	//----------------------------------------------------------------------------------------
	
	public function label ( $label )
	{
		$label_tag_string	=	'<label';
		
		$label_tag_string	.=	' for="'.$this->id.'" >';
		$label_tag_string	.=	$label;

		return $label_tag_string	.=	'</label>';
	}
	
	//----------------------------------------------------------------------------------------
	
	
	private function setAttributes ( $attributes_array )
	{
		$attributes_array	=	array_merge( $attributes_array, $this->global_attributes );
		$attributes			=	'';
		
		
		foreach ( $this as $index => $value )
			if ( $this->$index && in_array( $index, $attributes_array) )
				$attributes	.=	' '.$index.'="'.$value.'" ';
				
		return $attributes . $this->setBoolAttributes( $attributes_array );
	}
	
	private function setBoolAttributes ( $attributes_array )
	{
		$bool_attributes	=	'';
		
		if ( $this->disabled && in_array( 'disabled', $attributes_array) )
			$bool_attributes	.=	' disabled ';
		
		if ( $this->selected && in_array( 'selected', $attributes_array) )
			$bool_attributes	.=	' selected ';
			
		if ( $this->checked && in_array( 'checked', $attributes_array) )
			$bool_attributes	.=	' checked ';
			
		if ( $this->readonly && in_array( 'readonly', $attributes_array) )
			$bool_attributes	.=	' readonly ';
			
		if ( $this->multiple && in_array( 'multiple', $attributes_array) )
			$bool_attributes	.=	' multiple ';
		
		return $bool_attributes;
	}
	
	//----------------------------------------------------------------------------------------
	
	private function generateInput ()
	{
		$attributes_array	=	array (
			'accept', 'align', 'alt', 'autocomplete', 'autofocus',
			'checked', 'disabled', 'form', 'formaction', 'formenctype',
			'formmethod', 'formnovalidate', 'formtarget', 'height', 'list',
			'max', 'maxlength', 'min', 'multiple', 'name',
			'pattern', 'placeholder', 'readonly', 'required', 'size',
			'src', 'step', 'type', 'value', 'width'
		);
		
		$input	=	'<input ';
		
		$input	.=	' type="'.$this->type.'" ';

		$input	.=	$this->setAttributes ( $attributes_array );
		
		$input	.=	'/>';
		
		return $input;
	}
	
	//----------------------------------------------------------------------------------------
	
	private function generateTextArea ()
	{
		
		$attributes_array	=	array (
			'autofocus', 'cols', 'disabled', 'form', 'maxlength', 
			'name', 'placeholder', 'readonly', 'required', 'rows',
			'wrap'
		);
		
		$textarea	=	'<textarea ';
		$textarea	.=	$this->setAttributes ( $attributes_array );
		$textarea	.=	'>';
		$textarea	.=	$this->value;
		$textarea	.=	'</textarea>';
		
		return $textarea;
	}
	
	//----------------------------------------------------------------------------------------
	
	private function generateSelect ( $content )
	{
		$attributes_array	=	array (
			'autofocus', 'disabled', 'form', 'multiple', 'name', 
			'required', 'size'
		);
		
		$select_string	=	'<select ';
		$select_string	.=	$this->setAttributes( $attributes_array );
		$select_string	.=	'>';
		$select_string	.=	$content;
		
		return	$select_string	.=	'</select>';
		
	}
	
	//----------------------------------------------------------------------------------------
	
	private function generateOption ( $text )
	{
		$attributes_array	=	array (
			'disabled', 'label', 'selected', 'value'
		);
		
		$option_string	=	'<option ';
		$option_string	.=	$this->setAttributes( $attributes_array );
		$option_string	.=	!is_null ( $this->label ) ? 'label="'.$this->label.'"' : '' ;
		$option_string	.=	'>';
		$option_string	.=	$text;
		
		return	$option_string	.=	'</option>';
	}
	
	//----------------------------------------------------------------------------------------
	
	private function generateDatalist ( $content )
	{
		$attributes_array	=	array ( 'id' );
		
		$datalist_string	=	'<datalist ';
		$datalist_string	.=	$this->setAttributes( $attributes_array );
		$datalist_string	.=	'>';
		$datalist_string	.=	$content;
		
		return	$datalist_string	.=	'</datalist>';
	}
	
	//----------------------------------------------------------------------------------------
	
	private function generateOptionGroup ( $content )
	{
		$attributes_array	=	array (
			'disabled', 'label'
		);
		$select_string	=	'<optgroup ';
		$select_string	.=	!is_null ( $this->label ) ? 'label="'.$this->label.'"' : '';
		$select_string	.=	$this->setAttributes( $attributes_array );
		$select_string	.=	'>';
		$select_string	.=	$content;
		
		return	$select_string	.=	'</optgroup>';
	}
	
	//----------------------------------------------------------------------------------------
	
	private function generateButton ( $text )
	{
		$attributes_array	=	array (
			'autofocus', 'disabled', 'form', 'formaction', 'formenctype',
			'formmethod', 'formnovalidate', 'formtarget', 'name', 'type'
		);
		
		$button_string	=	'<button ';
		$button_string	.=	'type="'.$this->type."'	";
		$button_string	.=	$this->setAttributes( $attributes_array );
		$button_string	.=	'>';
		$button_string	.=	$text;
		
		return	$button_string	.=	'</button>';		
	}
	
	//----------------------------------------------------------------------------------------
	
	private function generateFieldSet ( $content )
	{
		$attributes_array	=	array (
			'disabled', 'form', 'name'
		);
		
		$field_set_string	=	'<fieldset ';
		$field_set_string	.=	$this->setAttributes( $attributes_array );
		$field_set_string	.=	'>';
		$field_set_string	.=	$this->generateLegend();
		$field_set_string	.=	$content;
		
		return	$field_set_string	.=	'</fieldset>';
	}
	
	//----------------------------------------------------------------------------------------
	
	private function generateLegend ()
	{
		return !is_null ( $this->legend ) ? ' <legend align="'.$this->align.'">'.$this->legend.'</legend>' : '';
	}
	
	//----------------------------------------------------------------------------------------
	
	public function enclose ( $content, $opening_tag, $closing_tag )
	{
		$enclose_set_string	=	$opening_tag;
		$enclose_set_string	.=	$content;
		
		return	$enclose_set_string	.=	$closing_tag;
	}
	
}