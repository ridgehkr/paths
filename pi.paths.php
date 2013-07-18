<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Memberlist Class
 *
 * @package     ExpressionEngine
 * @category	Plugin
 * @author      Caleb Pierce
 * @copyright   Copyright (c) 2013, Caleb Pierce
 * @link        http://calebpierce.com/
 */

$plugin_info = array(
	'pi_name' => 'Paths',
	'pi_version' => '1.0',
	'pi_author' => 'Caleb Pierce',
	'pi_author_url' => 'http://calebpierce.com/',
	'pi_description'=> 'A utility for gathering ExpressionEngine path information',
	'pi_usage' => Paths::usage()
);

class Paths {

	public function __construct() {

		$this->path = ee()->uri->uri_string();
		$this->segment_array = explode('/', $this->path);

	} // __construct

	public function full_path() {

		$return_data = $this->path;

		return $return_data;
	}

	public function total_segments() {

		return count($this->segment_array);

	}

	public function segments() {

		$output_array = array();

		foreach ( $this->segment_array as $segment )
			$output_array[] = array('segment' => $segment);

		$output = ee()->TMPL->parse_variables(ee()->TMPL->tagdata, $output_array);

		return $output;

	}

	public static function usage() {

		ob_start();  ?>

The Paths plugin allows you to output information about the ExpressionEngine URL path beyond the standard segment variables.

Usage:

{exp:paths:full_path}
Outputs all URL segments in order from first to last. For example, being called from http://example.com/path/to/my-page would result in /path/to/my-page

{exp:paths:total_segments}
Outputs the total number of segments in the URL. For example, if called from /path/to/my-page, it would output 3.

{exp:paths:segments}
	{segment}
{/exp:paths:segments}
Loops through the all segments and outputs them in order from first to last. Note that the "backspace" and "limit" parameters are available by default.

	<?php
        $buffer = ob_get_contents();
        ob_end_clean();

        return $buffer;
    }

} // class

/* End of file pi.plugin_name.php */
/* Location: ./manage/expressionengine/third_party/paths/pi.paths.php */