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
    $popup = $pluginParams->get('popup');
	$iconsize = $pluginParams->get('iconsize');
	if($popup == "0") {
		$popup = JText::_('JYES');
		$yeschecked = "checked=\"checked\"";
		$nochecked = "";
	}
	else {
		$popup = JText::_('JNO');
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
		'<label for=\"popup\" class=\"control-label\">Popup <span class=\"note\">[" . JText::_('JDEFAULT') .": " . $popup . "]</span></label>' +
		'<div class=\"controls\">' +
		'<input type=\"radio\" id=\"popup\" name=\"popup\" value=\"0\" " . $yeschecked . " /> " . JText::_('JYES') ." ' +
		'<input type=\"radio\" id=\"popup\" name=\"popup\" value=\"1\" " . $nochecked . " /> " . JText::_('JNO') ."</div>' +
		'</div>' +
	'<div class=\"control-group\">' +
		'<label for=\"iconsize\" class=\"control-label\">" . JText::_('PLG_EDITORS-XTD_EMBED_GOOGLE_MAP_ICONSIZE') . " (1-5) <span class=\"note\">[" . JText::_('JDEFAULT') .": " . $iconsize . "]</span></label>' +
		'<div class=\"controls\"><input maxlength=\"1\" class=\"num\" type=\"text\" id=\"iconsize\" name=\"iconsize\" /></div>' +
	'</div>' +
                                   '<button class=\"btn btn-primary done\" onclick=\"insertMap(' + editor + ');\">" . JText::_('PLG_EDITORS-XTD_EMBED_GOOGLE_MAP_INSERT') . "</button>' +
                                   '&nbsp;<button class=\"btn btn-primary\" onclick=\"SqueezeBox.close();\">" . JText::_('JCANCEL') . "</button>' +
                                '</div>'
	;

                                SqueezeBox.initialize();
                                SqueezeBox.setContent('string', htmlString);
			}
				function insertMap(editor) {
                 var myAddress = jQuery('#address').val();

                 var myWidth = jQuery('#width').val();
                 var myHeight = jQuery('#height').val();
				 if (myWidth != '' && myHeight != '') myAddress += '|width:' + myWidth + '|height:' + myHeight;

                 var myZoom = jQuery('#zoom').val();
				 if (myZoom != '') myAddress += '|zoom:' + myZoom;

				 var myPop = jQuery('input:radio[name=popup]:checked').val();
				 if (myPop == 0) myAddress += '|popup:yes';
				 
                 var myIconsize = jQuery('#iconsize').val();
				 if (myPop == 0 && myIconsize != '') myAddress += '|iconsize:' + myIconsize;

				 jInsertEditorText('{google_map}' + myAddress + '{/google_map}', '" . $name ."');
                  SqueezeBox.close();
                }
	";
		
        $doc->addScriptDeclaration($jsCode);

        $button = new JObject;
        $button->modal = true;
        $button->class = 'btn';
        $button->onclick = 'showInsertMapForm(\'' . $name . '\');return false;';
        $button->text = JText::_('PLG_EDITORS-XTD_EMBED_GOOGLE_MAP_BUTTON');
        $button->set('name', 'googleMapEditorButton');
	$button->options = "{handler: 'iframe', size: {x: 400, y: 550}}";
        $button->link = '#';

        return $button;
    }
}
