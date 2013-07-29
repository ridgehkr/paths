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
	'pi_version' => '1.1',
	'pi_author' => 'Caleb Pierce',
	'pi_author_url' => 'http://calebpierce.com/',
	'pi_description'=> 'A utility for gathering ExpressionEngine path information',
	'pi_usage' => Paths::usage()
);

class Paths {

	public function __construct() {

		$this->path = ee()->uri->uri_string(); // the full URI segment string
		$this->segment_array = explode('/', $this->path); // the above string as an array

	} // __construct


	/**
	 * full_path()
	 *
	 * Get the full URI segment path as a string
	 **/
	public function full_path() {

		$return_data = $this->path;

		return $return_data;
	}


	/**
	 * total_segments()
	 *
	 * Get the total number of segments in the URI string
	 **/
	public function total_segments() {

		return count($this->segment_array);

	}


	/**
	 * segments()
	 *
	 * This is a template tag pair that allows you to loop through each URI segment, one at a time
	 **/
	public function segments() {

		$output_array = array(); // the array to print to the template
		$reverse = ee()->TMPL->fetch_param('reverse'); // set to "yes" if the segment array should be reversed (i.e. bottom to top)

		// put the array into EE's variable pair format
		foreach ( $this->segment_array as $segment )
			$output_array[] = array('segment' => $segment);

		// reverse the array if desired
		if ( strtolower($reverse) == "yes" )
			$output_array = array_reverse($output_array);

		// prep output for the template
		$output = ee()->TMPL->parse_variables(ee()->TMPL->tagdata, $output_array);

		return $output;
	}


	/**
	 * ancestor_segment()
	 *
	 * Get the segment that is a certain number of levels above (tag param "steps_up")
	 **/
	public function ancestor_segment() {

		$steps = ee()->TMPL->fetch_param('steps_up'); // the tag argument for steps up the URI segments
		$segment = $this->_ancestor_segment($steps);

		return $segment;
	}


	/**
	 * path_until()
	 * 
	 * Public alias to _path_until()
	 * 
	 **/
	public function path_until() {

		$segment = ee()->TMPL->fetch_param('segment'); // the tag argument for the segment desired
		$path = $this->_path_until($segment);

		return $path;
	}


	/**
	 * path_until_ancestor()
	 *
	 * Get the URI path starting with the root and work down to the segment passed in.
	 **/
	public function path_until_ancestor() {

		$steps = ee()->TMPL->fetch_param('steps_up'); // the tag argument for steps up the URI segments

		$segment = $this->_ancestor_segment($steps);
		$path = $this->_path_until($segment, true);

		return $path;
	}


	/**
	 * _path_until()
	 *
	 * Get the path (from the root) until a certain segment has been reached. Optionally include the segment to stop at.
	 * 
	 * $segment -- the segment to stop at
	 * $include_segment -- whether or not to include the searched-for segment at the end of the path
	 **/
	private function _path_until( $segment, $include_segment ) {

		$path = '';

		// build the segment path until we've reached the segment to stop at
		foreach ( $this->segment_array as $i ) {
			// we've reached the segment in the URL path
			if ( $i == $segment ) {

				// include the end segment if requested
				if ( $include_segment ) {
					$path .= '/' . $segment;
				}

				// the target segment was the root, so indicate
				if ( $path == '' ) {
					$path .= '/';
				}
				
				break;
			}
			
			$path .= '/' . $i;
		}

		return $path;
	}


	/**
	 * _ancestor_segment()
	 *
	 * Get the segment a certain number of levels up from the end
	 *
	 * $steps_up -- the number of levels up to move to find the desired segment
	 **/
	private function _ancestor_segment( $steps_up ) {

		$segment = '';
		$steps = intval($steps_up);

		// determine the array index of the desired number of steps up
		$ancestor_index = count($this->segment_array) - ($steps + 1);

		// get the uri segment if it exists as selected
		if ( array_key_exists($ancestor_index, $this->segment_array) )
			$segment = $this->segment_array[$ancestor_index];

		return $segment;
	}


	/**
	 * usage()
	 *
	 * ExpressionEngine Control Panel usage information
	 **/
	public static function usage() {

		ob_start();  ?>

The Paths plugin allows you to output information about the ExpressionEngine URL path beyond the standard segment variables. Use it to gather information about your URI path which is generally not available when using custom URI paths from modules such as Structure.

Usage:

{exp:paths:full_path}
Outputs all URL segments in order from first to last. For example, being called from http://example.com/path/to/my-page would result in /path/to/my-page

{exp:paths:total_segments}
Outputs the total number of segments in the URL. For example, if called from /path/to/my-page, it would output 3.

{exp:paths:segments reverse="yes"}
{segment}
{/exp:paths:segments}
Loops through the all segments and outputs them in order from first to last. Note that the "backspace" parameter is available by default. Use the "reverse" parameter set to "yes" to optionally reverse the order of the segments

{exp:paths:path_until_ancestor steps_up="1"}
Starting from the root segment, outputs the URI path until the desired segment is reached. Use the "steps_up" parameter to specify how many levels up to stop at.

{exp:paths:ancestor_segment steps_up="1"}
Retrieves the URI segment that is the desired number of levels up in the path. Use the "steps_up" parameter to specify how many levels above the current segment the desired segment is.

	<?php
        $buffer = ob_get_contents();
        ob_end_clean();

        return $buffer;
    }

} // class

/* End of file pi.plugin_name.php */
/* Location: ./manage/expressionengine/third_party/paths/pi.paths.php */