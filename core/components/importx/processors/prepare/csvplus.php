<?php

require_once ('prepare.class.php');
class prepareCsvPlus extends prepareImport {

  public $debug =false;

    function process() {

        if ($this->debug) $this->importx->log('info', "<br>--------<br>prepareCsvPlus:");
        $lines = $this->parse_csv($this->data,$this->importx->config['separator']);
        if ($this->debug) $this->importx->log('info', "<br>--------<br>prepareCsvPlus2:".print_r($lines,true));

        if (count($lines) <= 1) {
            $this->importx->errors[] = $this->modx->lexicon('importx.err.notenoughdata');
            return false;
        }

        $headings = $lines[0];
        $headingcount = count($headings);
        $tvInHeadings = false;

        // Validate the headers...
        $fields = array('id', 'type', 'contentType', 'pagetitle', 'longtitle',  'alias', 'description', 'link_attributes', 'published', 'pub_date', 'unpub_date', 'parent', 'isfolder', 'introtext', 'content', 'richtext', 'template', 'menuindex', 'searchable', 'cacheable', 'createdby', 'createdon', 'editedby', 'editedon', 'deleted', 'deletedon', 'deletedby', 'publishedon', 'publishedby', 'menutitle', 'donthit', 'haskeywords', 'hasmetatags', 'privateweb', 'privatemgr', 'content_dispo', 'hidemenu', 'class_key', 'context_key', 'content_type', 'uri', 'uri_override');
        foreach ($headings as $h) {
            $h = trim($h);
            if (!in_array($h,$fields)) {
                if (substr($h,0,2) != 'tv') {
                    $this->importx->errors[] = $this->modx->lexicon('importx.err.invalidfield',array('field' => $h));
                }
                else {
                	$tvInHeadings = true;
                    if (intval(substr($h,2)) <= 0) {
                        $this->importx->errors[] = $this->modx->lexicon('importx.err.intexpected',array('field' => $h, 'int' => substr($h,2)));
                    } else {
                        $tvo = $this->modx->getObject('modTemplateVar',substr($h,2));
                        if (!$tvo) {
                            $this->importx->errors[] = $this->modx->lexicon('importx.err.tvdoesnotexist', array('field' => $h, 'id' => substr($h,2)));
                        }
                    }
                }
            }
        }

        if (count($this->importx->errors) > 0) {
            return false;
        }

        unset($lines[0]);
        $this->importx->log('info', $this->modx->lexicon('importx.log.preimportclean'));
        sleep(1);

        $err = array();
        foreach ($lines as $line => $curline) {

            if ($headingcount != count($curline)) {
                $err[] = $line + 1; // add one, because array reference is zero based, line reference in csv data is not
            } else {
                $lines[$line] = array_combine($headings,$curline);
                if (!isset($lines[$line]['context_key'])) { $lines[$line]['context_key'] = $this->importx->defaults['context_key']; }
                if (!isset($lines[$line]['parent'])) { $lines[$line]['parent'] = $this->importx->defaults['parent']; }
                if (!isset($lines[$line]['published'])) { $lines[$line]['published'] = $this->importx->defaults['published']; }
                if (!isset($lines[$line]['searchable'])) { $lines[$line]['searchable'] = $this->importx->defaults['searchable']; }
                if (!isset($lines[$line]['hidemenu'])) { $lines[$line]['hidemenu'] = $this->importx->defaults['hidemenu']; }
                if($tvInHeadings) $lines[$line]['tvs'] = true; // makes tvs save on update
            }
        }

        if ($this->debug) $this->importx->log('info', "<br>--------<br>prepareCsvPlus:<pre>".print_r($lines,true));

        if (count($err) > 0) {
            $this->importx->errors[] = $this->modx->lexicon('importx.err.elementmismatch',array('line' => implode(', ',$err)));
            return false;
        }
        return $lines;
    }


    /**
     * Returns multidimensional array from CSV string.
     * After a lot of tries and tests of several csv parse functions... this one seems to do really the job !
     * https://github.com/prcd/php-function-parse_csv
     * @param  [type] $string                     [description]
     * @param  string $field_delimiter            [description]
     * @param  string $field_encapsulator         [description]
     * @param  string $escaped_field_encapsulator [description]
     * @param  string $row_delimiter              [description]
     * @return [type]                             [description]
     */
    function parse_csv($string, $field_delimiter = ',', $field_encapsulator = '"', $escaped_field_encapsulator = '""', $row_delimiter = "\n")
    {

    	// establish key data
    	$string_length = strlen($string);

    	$field_delimiter_length = strlen($field_delimiter);
    	$field_encapsulator_length = strlen($field_encapsulator);
    	$escaped_field_encapsulator_length = strlen($escaped_field_encapsulator);
    	$row_delimiter_length = strlen($row_delimiter);

    	$escaped_field_encapsulator_contains_field_encapsulator = strpos($escaped_field_encapsulator, $field_encapsulator) === false ? false : true;

    	// initialise loop variables
    	$next_field_start = 0;

    	$next_field_delimiter = -1;
    	$next_row_delimiter = -1;

    	$row = 0;
    	$field = 0;

    	$arr = [];

    	do {
    		$string_position = $next_field_start;

    		// locate next field and row delimiter
    		if ($next_field_delimiter < $string_position) {
    			$next_field_delimiter = strpos($string, $field_delimiter, $string_position);
    		}
    		if ($next_row_delimiter < $string_position) {
    			$next_row_delimiter = strpos($string, $row_delimiter, $string_position);
    		}

    		// check for encapsulated field
    		if (substr($string, $string_position, $field_encapsulator_length) == $field_encapsulator) {

    			$search_offset = $string_position + $field_encapsulator_length;

    			$next_field_encapsulator = strpos($string, $field_encapsulator, $search_offset);

    			if ($escaped_field_encapsulator_contains_field_encapsulator) {

    				$next_escaped_field_encapsulator = strpos($string, $escaped_field_encapsulator, $search_offset);

    				// find the next field encapsulator that is not part of an escaped field encapsulator string
    				while ($next_field_encapsulator >= $next_escaped_field_encapsulator && $next_escaped_field_encapsulator !== false) {
    					$search_offset = $next_escaped_field_encapsulator + $escaped_field_encapsulator_length;

    					$next_field_encapsulator = strpos($string, $field_encapsulator, $search_offset);
    					$next_escaped_field_encapsulator = strpos($string, $escaped_field_encapsulator, $search_offset);
    				}
    			}

    			$search_offset = $next_field_encapsulator + $field_encapsulator_length;

    			$next_field_delimiter = strpos($string, $field_delimiter, $search_offset);
    			$next_row_delimiter = strpos($string, $row_delimiter, $search_offset);

    			$decode_field = true;
    		}
    		else {
    			$decode_field = false;
    		}

    		$this_row = $row;
    		$this_field = $field;
    		$field_start = $string_position;

    		if (($next_field_delimiter !== false && $next_row_delimiter !== false && $next_field_delimiter < $next_row_delimiter) || ($next_row_delimiter === false && $next_field_delimiter !== false)) {
    			// add a field to current row
    			$field_length = $next_field_delimiter - $field_start;
    			$field++;
    			// prepare for next iteration
    			$next_field_start = $next_field_delimiter + $field_delimiter_length;
    		}
    		else if ($next_row_delimiter !== false) {
    			// last section of current row, but there is another row
    			$field_length = $next_row_delimiter - $field_start;
    			$row++;
    			$field = 0;
    			// prepare for next iteration
    			$next_field_start = $next_row_delimiter + $row_delimiter_length;
    		}
    		else {
    			// no more field or row delimiters, whatever we have left is the last field
    			$field_length = $string_length - $string_position;
    			// exit after loop completion
    			$next_field_start = false;
    		}

    		if ($decode_field) {
    			// remove encapsulation & replace escaped field encapsulators
    			$arr[$this_row][$this_field] = str_replace($escaped_field_encapsulator, $field_encapsulator, substr($string, $string_position + $field_encapsulator_length, $field_length - (2 * $field_encapsulator_length)));
    		}
    		else {
    			$arr[$this_row][$this_field] = substr($string, $string_position, $field_length);
    		}
        $arr[$this_row][$this_field] = trim($arr[$this_row][$this_field]);

    	} while ($next_field_start !== false);

    	return $arr;
    }
}

/* Return the class name */
return 'prepareCsvPlus';
