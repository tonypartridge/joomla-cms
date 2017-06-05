<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

JFormHelper::loadFieldClass('list');

/**
 * Form Field class for the Joomla Platform.
 * Supports a list of installed application languages
 *
 * @see    JFormFieldContentLanguage for a select list of content languages.
 * @since  11.1
 */
class JFormFieldAllLanguages extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $type = 'AllLanguages';

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   11.1
	 */
	protected function getOptions()
	{

		// Get all languages of frontend and backend.
		$languages       = array();
		$site_languages  = JLanguageHelper::getKnownLanguages(JPATH_SITE);
		$admin_languages = JLanguageHelper::getKnownLanguages(JPATH_ADMINISTRATOR);

		// Create a single array of them.
		foreach ($site_languages as $tag => $language)
		{
			$languages[$tag . '0'] = JText::sprintf('COM_LANGUAGES_VIEW_OVERRIDES_LANGUAGES_BOX_ITEM', $language['name'], JText::_('JSITE'));
		}

		foreach ($admin_languages as $tag => $language)
		{
			$languages[$tag . '1'] = JText::sprintf('COM_LANGUAGES_VIEW_OVERRIDES_LANGUAGES_BOX_ITEM', $language['name'], JText::_('JADMINISTRATOR'));
		}

		// Sort it by language tag and by client after that.
		ksort($languages);

		// Add the languages to the internal cache.
		$languages;

		// Merge any additional options in the XML definition.
		$options = array_merge(
			parent::getOptions(),
			$languages
		);

		// Set the default value active language
		if ($langParams = JComponentHelper::getParams('com_languages'))
		{
			switch ((string) $this->value)
			{
				case 'site':
				case 'frontend':
				case '0':
					$this->value = $langParams->get('site', 'en-GB');
					break;
				case 'admin':
				case 'administrator':
				case 'backend':
				case '1':
					$this->value = $langParams->get('administrator', 'en-GB');
					break;
				case 'active':
				case 'auto':
					$lang = JFactory::getLanguage();
					$this->value = $lang->getTag();
					break;
				default:
					break;
			}
		}

		return $options;
	}
}