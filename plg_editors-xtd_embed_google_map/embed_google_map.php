<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Content.embed_google_map
 *
 * @copyright   Copyright (C) All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.utilities.utility');

/**
 * Inserting maps plugin
 *
 *
 * @package     Joomla.Plugin
 * @subpackage  Content.embed_google_map
 * @since       3.3.1
 */
class PlgButtonembed_google_map extends JPlugin
{
    /**
     * Load the language file on instantiation.
     *
     * @var    boolean
     * @since  3.1
     */
    protected $autoloadLanguage = true;

    /**
     * Display the button
     *
     * @param   string  $name  The name of the button to add
     *
     * @return array A two element array of (imageName, textToInsert)
     */
    public function onDisplay($name)
    {
		JFactory::getLanguage()->load('plg_editors-xtd_embed_google_map');
		$doc = JFactory::getDocument();
    $doc->addStyleSheet(JURI::root() . 'plugins/editors-xtd/embed_google_map/embed_google_map.css');

// Get plugin 'embed_google_map' of type 'content'
$plugin = JPluginHelper::getPlugin('content', 'embed_google_map');
// Check if plugin is enabled
if ($plugin)
{
    // Get plugin params
    $pluginParams = new JRegistry($plugin->params);
    $width = $pluginParams->get('width');
    $height = $pluginParams->get('height');
    $zoom = $pluginParams->get('zoom');
    $jcemediabox = $pluginParams->get('jcemediabox');
	if($jcemediabox == "1") {
		$jcemediabox = JText::_('JYES');
		$yeschecked = "checked=\"checked\"";
		$nochecked = "";
	}
	else {
		$jcemediabox = JText::_('JNO');
		$yeschecked = "";
		$nochecked = "checked=\"checked\"";
	}
}	

		$jsCode = "
            function showInsertMapForm(editor)
            {
                var htmlString = '<div id=\"insertDefinitionForm\">' +
	'<div class=\"control-group\">' +
		'<label for=\"address\" class=\"control-label\">" . JText::_('PLG_EDITORS-XTD_EMBED_GOOGLE_MAP_ADDRESS') . " <span class=\"note\">[" . JText::_('PLG_EDITORS-XTD_EMBED_GOOGLE_MAP_ADDRESS_NOTE') . "]</span></label>' +
		'<div class=\"controls\"><input class=\"txt\" type=\"text\" id=\"address\" name=\"address\" /></div>' +
	'</div>' +
	'<div class=\"control-group\">' +
		'<label for=\"width\" class=\"control-label\">" . JText::_('PLG_EDITORS-XTD_EMBED_GOOGLE_MAP_WIDTH') . " <span class=\"note\">[" . JText::_('JDEFAULT') .": " . $width . "]</span></label>' +
		'<div class=\"controls\"><input maxlength=\"4\" class=\"num\" type=\"text\" id=\"width\" name=\"width\" /></div>' +
	'</div>' +
	'<div class=\"control-group\">' +
		'<label for=\"height\" class=\"control-label\">" . JText::_('PLG_EDITORS-XTD_EMBED_GOOGLE_MAP_HEIGHT') . " <span class=\"note\">[" . JText::_('JDEFAULT') .": " . $height . "]</span></label>' +
		'<div class=\"controls\"><input maxlength=\"4\" class=\"num\" type=\"text\" id=\"height\" name=\"height\" /></div>' +
	'</div>' +
	'<div class=\"control-group\">' +
		'<label for=\"zoom\" class=\"control-label\">Zoom (0-21) <span class=\"note\">[" . JText::_('JDEFAULT') .": " . $zoom . "]</span></label>' +
		'<div class=\"controls\"><input maxlength=\"2\" class=\"num\" type=\"text\" id=\"zoom\" name=\"zoom\" /></div>' +
	'</div>' +
	'<div class=\"control-group\">' +
		'<label for=\"jcemediabox\" class=\"control-label\">JCE Mediabox <span class=\"note\">[" . JText::_('JDEFAULT') .": " . $jcemediabox . "]</span></label>' +
		'<div class=\"controls\">' +
		'<input type=\"radio\" id=\"jcemediabox\" name=\"jcemediabox\" value=\"1\" " . $yeschecked . " /> " . JText::_('JYES') ." ' +
		'<input type=\"radio\" id=\"jcemediabox\" name=\"jcemediabox\" value=\"0\" " . $nochecked . " /> " . JText::_('JNO') ."' + '</div>' +
	'</div>' +
                                   '<button class=\"btn btn-primary done\" onclick=\"insertMap(' + editor + ');\">" . JText::_('PLG_EDITORS-XTD_EMBED_GOOGLE_MAP_INSERT') . "</button>' +
                                   '&nbsp;<button class=\"btn btn-primary\" onclick=\"SqueezeBox.close();\">" . JText::_('JCANCEL') . "</button>' +
                                '</div>'
	;

                                var options = {size: {x: 300, y: 150}};
                                SqueezeBox.initialize(options);
                                SqueezeBox.setContent('string', htmlString);
			}
				function insertMap(editor) {
                 var myAddress = jQuery('#address').val();

                 var myWidth = jQuery('#width').val();
                 var myHeight = jQuery('#height').val();
				 if (myWidth != '' && myHeight != '') myAddress += '|width:' + myWidth + '|height:' + myHeight;

                 var myZoom = jQuery('#zoom').val();
				 if (myZoom != '') myAddress += '|zoom:' + myZoom;

				 var myPop = jQuery('input:radio[name=jcemediabox]:checked').val();
				 if (myPop == 1) myAddress += '|popup:yes';
				 
				 jInsertEditorText('{google_map}' + myAddress + '{/google_map}', editor);
                  SqueezeBox.close();
                }
	";
		
        $doc->addScriptDeclaration($jsCode);

        $button = new JObject;
        $button->modal = false;
        $button->class = 'btn';
        $button->onclick = 'showInsertMapForm(\'' . $name . '\');return false;';
        $button->text = JText::_('PLG_EDITORS-XTD_EMBED_GOOGLE_MAP_BUTTON');
        $button->name = 'list';

        $button->link = '#';

        return $button;
    }
}