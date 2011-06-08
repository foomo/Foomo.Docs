<?php
/**
 * from Horde: wicked/lib/Text_Wiki/Render/Xhtml/Geshi.php,v 1.2 2006/02/27 14:59:04 tasin Exp
 * 
 *
 * This adapter class uses GeSHi (Generic Syntax Highlighter)
 * by Nigel McNie, (C) 2004, released under the GNU GPL
 * http://qbnz.com/highlighter
 *
 * 
 */
class Text_Wiki_Render_Xhtml_Geshi extends Text_Wiki_Render {

    var $conf = array(
        'charset' => 'UTF-8',
        /** create a map which method does the style changement 
            code style keys not mentioned always use the standard method name:
            "set_<key>_style" (e.g. "set_comments_style")
        */
        'geshi_methods' => array(
           'keywords' => 'set_keyword_group_style',
           'escape_chars' => 'set_escape_characters_style',
        ),
        'css_code' => array( 
           'keywords' => array('1' => 'color:#007700;', '2' => 'color:#007700;', 
                              '3' => 'color: #666688; font-weight: bold;', '4' => 'color:#007700;'),
           'comments' => array('1' => 'color:#FF8000;', '2' => null, 
                               'MULTI' => 'color:#FF8000;'),
           'regexps' => array(),
           'methods' => array('1' => 'color:#804000;', '2' => 'color:#804000;'),
           'symbols' => null,
           'strings' => null,
           'escape_chars' => null,
           'numbers' => null
        ),
        /** You can specify for each programming language an css_array like above.
            Name it simply "css_<name of the language file>" like in the following example 
            for mysql, which resets all the values back to those defined
            in the language file "geshi/geshi/mysql.php"
        */
        'css_mysql' => array( 
           'keywords' => array(),
           'comments' => array(),
           'regexps' => array(),
           'methods' => array(),
           'symbols' => null,
           'strings' => null,
           'escape_chars' => null,
           'numbers' => null
        ),
    );

    /**
     * Renders a token into text matching the requested format.
     *
     * @param array $options The "options" portion of the token (second
     * element).
     *
     * @return string The text rendered from the token options.
     */
    function token($options)
    {
        $charset = $this->getConf('charset', 'UTF-8');
        $geshi_dir = $this->getConf('geshi_dir', dirname(__FILE__) . '/geshi');
        $lang_array = $this->getConf('css_code', array());
        $methods_array=$this->getConf('geshi_methods', array());
        
        $type = $options['attr']['type'];
		if(isset($options['attr']['file']) && file_exists($options['attr']['file'])) {
	        $text = file_get_contents($options['attr']['file']);
		} else {
	        $text = $options['text'];
		}
		$text = trim($text);
        if (@file_exists($geshi_dir . '/geshi.php')) {
            @include_once $geshi_dir . '/geshi.php';
        } else {
            @include_once 'geshi/geshi.php';
        }
        
        if (class_exists('GeSHi')) {
            $geshi = new GeSHi($text, $type);
            // set up the charset
            $geshi->set_encoding($charset);
            
            // disable all external url creations 
            for ($i = 1; $i < 4; $i++) {
                $geshi->set_url_for_keyword_group($i, '');
            }
            
            // check if there are special settings for the desired language
            if (is_array($this->getConf('css_'. $type, null))) {
                $lang_array=$this->getConf('css_'. $type, array());
            }
            if (is_array($lang_array)) {
                foreach ($lang_array as $codestyle => $value) {
                    $method = (empty($methods_array[$codestyle])) ? 'set_' . $codestyle . '_style' :
                        $methods_array[$codestyle];
                    if (is_array($lang_array[$codestyle])) {
                        // set up the grouped styles (like keyword, comments, regexps, ...)
                        foreach ($lang_array[$codestyle] as $group => $style) {
                            if (@method_exists($geshi, $method) && !is_null($style)) {
                                @call_user_func(array(&$geshi, $method), $group, $style);
                            }
                        }
                    } else {
                        // set up the value styles (like strings, numbers, ...)
                        if (@method_exists($geshi, $method) && !is_null($value)) {
                            @call_user_func(array(&$geshi, $method), $value);
                        }
                    }
                }
            }
            
            // check if the user wants line numbers 
            if (isset($options['attr']['start']) && $options['attr']['start'] > 0) {
                $geshi->enable_line_numbers(GESHI_NORMAL_LINE_NUMBERS);//GESHI_FANCY_LINE_NUMBERS);
                $geshi->start_line_numbers_at($options['attr']['start']);
            }

            // check if the user wants to specify the tab width 
            /* ... doesn't work here ... 
                because 'Prefilter' is called first
                and the block is done with <pre>...</pre>
            if (isset($options['attr']['tab_width']) && $options['attr']['tab_width'] > 0) {
                $geshi->set_tab_width($options['attr']['tab_width']);
            }
            */
        
            if ($error=$geshi->error()) {
                $text="<pre><code>" . 
                sprintf("<b>Geshi error</b> while parsing language '%s'", $type) .
                    "</code></pre>";
            } else {
                $text= '<div class="geshiCode"><!-- geshi start -->' . $geshi->parse_code() . '<!-- geshi end --></div>';
            }
        } else {
          $text="<pre><code>" . _("Cannot find <b>Geshi class</b>.") . "</code></pre>";
        }
        return $text;
    }

}
