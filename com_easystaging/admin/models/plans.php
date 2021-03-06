<?php
/**
 * EasyStaging Model for EasyStaging Component
 * @link		http://seepeoplesoftware.com
 * @license		GNU/GPL
 * @copyright	Craig Phillips Pty Ltd
 */
 
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();
 
jimport( 'joomla.application.component.modellist' );
 
/**
 * EasyStaging Model
 *
 */
class EasyStagingModelPlans extends JModelList
{
	/**
	 * Method to get an array of data items.
	 *
	 * @return  mixed  An array of data items on success, false on failure.
	 *
	 * @since   11.1
	 */
	public function getItems()
	{
		// Let the parent do the heavy lifting
		$items = parent::getItems();

		foreach ($items as &$item)
		{
			$item->lastRunDTS  = JHtml::_('date.relative', $item->last_run);

            $lrb = $item->last_run_by;
            if (is_null($lrb) || $lrb == 0 || empty($lrb)) {
                $item->last_run_by = '';
            } else {
                $item->last_run_by = JFactory::getUser($item->last_run_by)->name;
            }

			if($item->checked_out)
			{
				$editor       = JFactory::getUser($item->checked_out);
				$item->editor = JText::sprintf('COM_EASYSTAGING_PLANS_CHECKED_OUT_BY_X_AKA_Y', $editor->username, $editor->name);
			}
		}

			// Return our items
		return $items;
	}

	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return	string	An SQL query
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		// Select some fields
		$query->select($db->quoteName('id'));
		$query->select($db->quoteName('name'));
		$query->select($db->quoteName('description'));
		$query->select($db->quoteName('published'));
		$query->select($db->quoteName('created_by'));
		$query->select($db->quoteName('publish_down'));
		$query->select($db->quoteName('publish_up'));
		$query->select($db->quoteName('checked_out'));
		$query->select($db->quoteName('checked_out_time'));
		$query->select($db->quoteName('modified'));
		$query->select($db->quoteName('modified_by'));
		$query->select($db->quoteName('last_run'));
        $query->select($db->quoteName('last_run_by'));

		// From the EasyStaging table
		$query->from('#__easystaging_plans');

		return $query;
	}
}
