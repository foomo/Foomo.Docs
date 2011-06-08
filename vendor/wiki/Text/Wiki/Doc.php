<?php
class Text_Wiki_Doc extends Text_Wiki {

    var $rules = array(
        'Prefilter',
        'Delimiter',
        'Code',
    	'Geshi',
        'Raw',
        'Heading',
        'Horiz',
        'Break',
        'Blockquote',
        'List',
        'Deflist',
        'Table',
        'Image',
        'Phplookup',
        'Newline',
        'Paragraph',
        'Url',
        'Doclink',
        'Colortext',
        'Strong',
        'Bold',
        'Emphasis',
        'Italic',
        'Underline',
        'Tt',
        'Superscript',
        'Subscript',
        'Revise',
        'Tighten'
    );

    function Text_Wiki_Doc($rules = null) {
        parent::Text_Wiki($rules);
		$this->formatConf['Xhtml']['translate'] = HTML_SPECIALCHARS;        
        $this->setRenderConf('Xhtml', 'Url', 'target', '');
        $this->setRenderConf('Xhtml', 'charset', 'UTF-8');
    }
}