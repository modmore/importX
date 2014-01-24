<?php

require_once ('prepare.class.php');
class prepareCsv extends prepareImport {
    function process() {
        $lines = explode("\n",$this->data);
        if (count($lines) <= 1) {
            $this->importx->errors[] = $this->modx->lexicon('importx.err.notenoughdata');
            return false;
        }
        
        $headingline = trim($lines[0]);
        if (substr($headingline,-strlen($this->importx->config['separator'])) == $this->importx->config['separator']) {
            $headingline = substr($headingline,0,-strlen($this->importx->config['separator']));
        }
        $headings = explode($this->importx->config['separator'],$headingline);
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
        foreach ($lines as $line => $lineval) {
            $lineval = trim($lineval);
            if (substr($lineval,-strlen($this->importx->config['separator'])) == $this->importx->config['separator']) {
                $lineval = substr($lineval,0,-strlen($this->importx->config['separator']));
            }
            
            $curline = explode($this->importx->config['separator'],$lineval);
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
        if (count($err) > 0) {
            $this->importx->errors[] = $this->modx->lexicon('importx.err.elementmismatch',array('line' => implode(', ',$err)));
            return false;
        }
        return $lines;
    }
}

/* Return the class name */
return 'prepareCsv';
