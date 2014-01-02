<?php 
class htmlRenderer
{
	
	public static function makeSelectInput($values, $name, $first_value, $selected_value, $class = "")
	{
		$dropdown = '<select name="'.$name.'" class="'.$class.'">';
		$dropdown .= "<option>$first_value</option>";
	
		if($selected_value == NULL)
			foreach($values as $value)
				$dropdown .= '<option value="'.$value.'">'.$value.'</option>';
		else
		{
			foreach($values as $value)
			{
				if($value == $selected_value)
					$dropdown .= '<option  selected="selected" value="'.$value.'">'.$value.'</option>';
				else
					$dropdown .= '<option value="'.$value.'">'.$value.'</option>';
			}
		}

		$dropdown .= "</select>";

		return $dropdown;
	}
	
	//-------------------------------------------------------------------------------------------------------------------
	
	public static function makeRadioButtons($values, $name, $selected_value, $is_vertical_display = TRUE, $class="")
	{
		$radio = "";
		if($is_vertical_display)
		{
			foreach($values as $label => $value)
			{
				if($value == $selected_value)
					$radio .= "<span><input type='radio' class='{$class}' name='{$name}' checked value='{$value}' /></span><span>{$label}</span>";
				else
					$radio .= "<span><input type='radio' class='{$class}' name='{$name}' value='{$value}' /></span><span>{$label}</span>";
			}
		}
		else
		{
			foreach($values as $label => $value)
			{
				if($value == $selected_value)
					$radio .= "<div><span><input type='radio' class='{$class}' name='{$name}' checked value='{$value}' /></span><span>{$label}</span></div>";
				else
					$radio .= "<div><span><input type='radio' class='{$class}' name='{$name}' value='{$value}' /></span><span>{$label}</span></div>";
			}
		}

		return $radio;
	}
	
	//-------------------------------------------------------------------------------------------------------------------

	public static function getArrayItems($index)
	{
		switch($index)
		{
			case "months" : 
			{
				$months = array();
				for( $i=1; $i <= 12; $i++ )
					$months[] = date("F", strtotime("01-$i-2013"));
				
				return $months;
			} break;
			
			case "years" : 
			{
				$years = array();
				for( $i=1980; $i <= 2013; $i++ )
					$years[] = $i;
					
				return $years;
			}
			default:
				return NULL;
		}
	}
	
	//-------------------------------------------------------------------------------------------------------------------
	
	public static function makeDatePicker($values, $name)
	{
		$date_picker  = self::makeSelectInput(self::getArrayItems("months"), $name."_months", "Months", $values["months"]);
		$date_picker .= self::makeSelectInput(range(1, 31), $name."_days", "Days", $values["days"]);
		$date_picker .= self::makeSelectInput(self::getArrayItems("years"), $name."_years", "Years", $values["years"]);
		
		return $date_picker;
	}
	
}


?>