<?php
/**
* 
* Parses for text marked as a code example block 
* and renders with GeSHi.
* original file: Paul M. Jones <pmjones@php.net>
*
* This adapter class uses GeSHi (Generic Syntax Highlighter)
* by Nigel McNie, (C) 2004, released under the GNU GPL
* http://qbnz.com/highlighter
* 
* @category Text
* 
* @package Text_Wiki
* 
* @author Walter Tasin <tasin@fhm.edu>
*
* @license LGPL
* 
* @version $Id: Geshi.php,v 1.0 2006/02/24 16:52:03 tasin Exp $
* 
*/

/**
* 
* Parses for text marked as a code example block.
* 
* This class implements a Text_Wiki_Parse to find sections marked as code
* examples.  Blocks are marked as the string <geshi> on a line by itself,
* followed by the inline code example, and terminated with the string
* </geshi> on a line by itself.  The code example is run through the
* GeSHI generic syntax highlighter to colorize it, then surrounded
* with <pre>...</pre> tags when rendered as XHTML.
*
* @category Text
* 
* @package Text_Wiki
* 
* @author Walter Tasin <tasin@fhm.edu>
* 
*/

class Text_Wiki_Parse_Geshi extends Text_Wiki_Parse {
    
    
    /**
    * 
    * The regular expression used to find source text matching this
    * rule.
    * 
    * @access public
    * 
    * @var string
    * 
    */
    
    /*var $regex = ';^<geshi(\s[^>]*)?>((?:(?R)|.)*?)\n</geshi>(\s|$);msi';*/
    var $regex   = ';^<geshi(\s[^>]*)?>((?:(?R)|.*?)*)\n</geshi>(\s|$);msi';
    /**
    * 
    * Generates a token entry for the matched text.  Token options are:
    * 
    * 'text' => The full matched text, not including the <geshi></geshi> tags.
    * 
    * @access public
    *
    * @param array &$matches The array of matches from parse().
    *
    * @return A delimited token number to be used as a placeholder in
    * the source text.
    *
    */
    
    function process(&$matches)
    {
        // are there additional attribute arguments?
        $args = trim($matches[1]);
        
        if ($args == '') {
            $options = array(
                'text' => $matches[2],
                'attr' => array('type' => '', 'start' => '',  'tab_width' => '')
            );
        } else {
        	// get the attributes...
        	$attr = $this->getAttrs($args);
        	
        	// ... and make sure we have a 'type'
        	if (! isset($attr['type'])) {
        		$attr['type'] = '';
        	}
        	// retain the options
            $options = array(
                'text' => $matches[2],
                'attr' => $attr
            );
        }
        
        return $this->wiki->addToken($this->rule, $options) . $matches[3];
    }
}
