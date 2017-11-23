<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('behavior.formvalidator');
JHtml::_('script', 'com_users/admin-users-user.min.js', array('version' => 'auto', 'relative' => true));

// Get the form fieldsets.
$fieldsets = $this->form->getFieldsets();
$settings  = array();
?>

<form action="<?php echo JRoute::_('index.php?option=com_users&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="user-form" enctype="multipart/form-data" class="form-validate">
	<fieldset>
		<div class="row">
			<div class="col-md-12">
				<div class="control-group">
					<div class="control-label">
						<?php echo $this->form->getlabel('name'); ?>
					</div>
					<div class="controls">
						<?php echo $this->form->getInput('name'); ?>
					</div>
				</div>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'details')); ?>

			<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'details', JText::_('COM_USERS_USER_ACCOUNT_DETAILS')); ?>
				<?php foreach ($this->form->getFieldset('user_details') as $field) : ?>
					<div class="control-group">
						<div class="control-label">
								<?php echo $field->label; ?>
						</div>
						<div class="controls">
							<?php echo $field->input; ?>
						</div>
					</div>
				<?php endforeach; ?>
			<?php echo JHtml::_('bootstrap.endTab'); ?>

			<?php if ($this->grouplist) : ?>
				<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'groups', JText::_('COM_USERS_ASSIGNED_GROUPS')); ?>
					<?php echo $this->loadTemplate('groups'); ?>
				<?php echo JHtml::_('bootstrap.endTab'); ?>
			<?php endif; ?>

			<?php
			$this->ignore_fieldsets = array('user_details');
			echo JLayoutHelper::render('joomla.edit.params', $this);
			?>

		<?php if (!empty($this->tfaform) && $this->item->id) : ?>
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'twofactorauth', JText::_('COM_USERS_USER_TWO_FACTOR_AUTH')); ?>
		<div class="control-group">
			<div class="control-label">
				<label id="jform_twofactor_method-lbl" for="jform_twofactor_method" class="hasTooltip"
					title="<?php echo '<strong>' . JText::_('COM_USERS_USER_FIELD_TWOFACTOR_LABEL') . '</strong>'; ?>">
					<?php echo JText::_('COM_USERS_USER_FIELD_TWOFACTOR_LABEL'); ?>
				</label>
			</div>
			<div class="controls">
				<?php echo JHtml::_('select.genericlist', Usershelper::getTwoFactorMethods(), 'jform[twofactor][method]', array('onchange' => 'Joomla.twoFactorMethodChange()', 'class' => 'custom-select'), 'value', 'text', $this->otpConfig->method, 'jform_twofactor_method', false); ?>
			</div>
		</div>
		<div id="com_users_twofactor_forms_container">
			<?php foreach ($this->tfaform as $form) : ?>
			<?php $style = $form['method'] == $this->otpConfig->method ? 'display: block' : 'display: none'; ?>
			<div id="com_users_twofactor_<?php echo $form['method'] ?>" style="<?php echo $style; ?>">
				<?php echo $form['form'] ?>
			</div>
			<?php endforeach; ?>
		</div>

		<fieldset>
			<legend>
				<?php echo JText::_('COM_USERS_USER_OTEPS'); ?>
			</legend>
			<div class="alert alert-info">
				<?php echo JText::_('COM_USERS_USER_OTEPS_DESC'); ?>
			</div>
			<?php if (empty($this->otpConfig->otep)) : ?>
			<div class="alert alert-warning">
				<?php echo JText::_('COM_USERS_USER_OTEPS_WAIT_DESC'); ?>
			</div>
			<?php else : ?>
			<?php foreach ($this->otpConfig->otep as $otep) : ?>
			<span class="col-md-3">
				<?php echo substr($otep, 0, 4); ?>-<?php echo substr($otep, 4, 4); ?>-<?php echo substr($otep, 8, 4); ?>-<?php echo substr($otep, 12, 4); ?>
			</span>
			<?php endforeach; ?>
			<?php endif; ?>
		</fieldset>

		<?php echo JHtml::_('bootstrap.endTab'); ?>
		<?php endif; ?>

		<?php echo JHtml::_('bootstrap.endTabSet'); ?>
	</fieldset>

	<input type="hidden" name="task" value="">
	<?php echo JHtml::_('form.token'); ?>
</form>
