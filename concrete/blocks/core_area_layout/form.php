<?php
    defined('C5_EXECUTE') or die('Access Denied.');

    $minColumns = 1;
    $columnsNum = $columnsNum ?? 1;
    $maxColumns = $maxColumns ?? 12;
    $enableThemeGrid = $enableThemeGrid ?? false;
    /** @var \Concrete\Block\CoreAreaLayout\Controller $controller */
    /** @var \Concrete\Core\Block\Block $b */
    /** @var \Concrete\Core\Block\View\BlockView $view */
    /** @var \Concrete\Core\Area\Area $a */
    /** @var \Concrete\Core\Page\Theme\GridFramework\GridFramework $themeGridFramework */
    if ($controller->getTask() == 'add') {
        $spacing = 0;
        $iscustom = false;
    }
    $presets = app('manager/area_layout_preset_provider')->getPresets();
?>

<ul id="ccm-layouts-toolbar" class="ccm-inline-toolbar ccm-ui">
	<li class="ccm-sub-toolbar-text-cell">
		<label for="useThemeGrid"><?=t('Grid:')?></label>
		<select name="gridType" id="gridType" style="width: auto !important">
			<optgroup label="<?=t('Grids')?>">
			<?php if ($enableThemeGrid) {
    ?>
				<option value="TG"><?=$themeGridName ?? ''?></option>
			<?php
} ?>
			<option value="FF"><?=t('Free-Form Layout')?></option>
			</optgroup>
			<?php if (count($presets) > 0) {
    ?>
			<optgroup label="<?=t('Presets')?>">
			  	<?php foreach ($presets as $pr) {
    ?>
				    <option value="<?=$pr->getIdentifier()?>" <?php if (isset($selectedPreset) && is_object($selectedPreset) && $selectedPreset->getIdentifier() == $pr->getIdentifier()) {
    ?>selected<?php
}
    ?>><?=$pr->getName()?></option>
				<?php
}
    ?>
			</optgroup>
			<?php
} ?>
		</select>
	</li>
	<li data-grid-form-view="themegrid">
		<label for="themeGridColumns"><?=t('Columns:')?></label>
		<input type="number" name="themeGridColumns" id="themeGridColumns" style="width: 40px" <?php if ($controller->getTask() == 'add') {
    ?>  min="<?=$minColumns?>" max="<?= $themeGridMaxColumns ?? '' ?>" <?php
} ?> value="<?=$columnsNum?>" />
		<?php if ($controller->getTask() == 'edit') {
    // we need this to actually go through the form in edit mode, for layout presets to be saveable in edit mode.?>
			<input type="hidden" name="themeGridColumns" value="<?=$columnsNum?>" />
		<?php
} ?>
	</li>
	<li data-grid-form-view="custom" class="ccm-sub-toolbar-text-cell">
		<label for="columns"><?=t('Columns:')?></label>
		<input type="number" name="columns" id="columns" style="width: 40px" <?php if ($controller->getTask() == 'add') {
    ?> min="<?=$minColumns?>" max="<?=$maxColumns?>" <?php
} ?> value="<?=$columnsNum?>" />
		<?php if ($controller->getTask() == 'edit') {
    // we need this to actually go through the form in edit mode, for layout presets to be saveable in edit mode.?>
			<input type="hidden" name="columns" value="<?=$columnsNum?>" />
		<?php
} ?>
	</li>
	<li data-grid-form-view="custom">
		<label for="columns"><?=t('Spacing:')?></label>
		<input name="spacing" id="spacing" type="number" style="width: 40px" min="0" max="1000" value="<?=$spacing ?? ''?>" />
	</li>
	<li data-grid-form-view="custom" class="ccm-inline-toolbar-icon-cell <?php if (empty($iscustom)) {
    ?>ccm-inline-toolbar-icon-selected<?php
} ?>"><a href="#" data-layout-button="toggleautomated"><i class="fas fa-lock"></i></a>
		<input type="hidden" name="isautomated" value="<?php if (empty($iscustom)) {
    ?>1<?php
} else {
    ?>0<?php
} ?>" />
	</li>
	<?php if ($controller->getTask() == 'edit') {
    $bp = new \Concrete\Core\Permission\Checker($b);
    ?>

		<li class="ccm-inline-toolbar-icon-cell"><a href="#" data-layout-command="move-block"><i class="fas fa-arrows-alt"></i></a></li>

		<?php
        /** @phpstan-ignore-next-line */
        if ($bp->canDeleteBlock()) {
            $deleteMessage = t('Do you want to delete this layout? This will remove all blocks inside it.');
            ?>
			<li class="ccm-inline-toolbar-icon-cell"><a href="#" data-menu-action="delete-layout"><i class="fas fa-trash"></i></a></li>
		<?php
        }
    ?>
	<?php
} ?>

	<li class="ccm-inline-toolbar-button ccm-inline-toolbar-button-cancel">
		<button id="ccm-layouts-cancel-button" type="button" class="btn btn-mini"><?=t('Cancel')?></button>
	</li>
	<li class="ccm-inline-toolbar-button ccm-inline-toolbar-button-save">
	  <button class="btn btn-primary" type="button" id="ccm-layouts-save-button"><?php if ($controller->getTask() == 'add') {
    ?><?=t('Add Layout')?><?php
} else {
    ?><?=t('Update Layout')?><?php
} ?></button>
	</li>
</ul>

	<?php if ($controller->getTask() == 'add') {
    ?>
		<input name="arLayoutMaxColumns" type="hidden" value="<?=$view->getAreaObject()->getAreaGridMaximumColumns()?>" />
	<?php
} ?>

<script type="text/javascript">
<?php

if ($controller->getTask() == 'edit') {
    $editing = 'true';
} else {
    $editing = 'false';
}

?>

$(function() {


	<?php
    if ($controller->getTask() == 'edit') {
        ?>
	$('#ccm-layouts-toolbar').on('click', 'a[data-menu-action=delete-layout]', function(e) {
		var editor = new Concrete.getEditMode(),
			area = editor.getAreaByID(<?=$a->getAreaID()?>),
			block = area.getBlockByID(<?=$b->getBlockID()?>);

		ConcreteEvent.subscribe('EditModeBlockDeleteAfterComplete', function() {
			editor.destroyInlineEditModeToolbars();
			ConcreteEvent.unsubscribe('EditModeBlockDeleteAfterComplete');
		});

		Concrete.event.fire('EditModeBlockDelete', {message: '<?=$deleteMessage ?? ''?>', block: block, event: e});
		return false;
	});
	<?php
    } ?>

	$('#ccm-layouts-edit-mode').concreteLayout({
		'editing': <?=$editing?>,
		'supportsgrid': '<?=$enableThemeGrid?>',
		<?php if ($enableThemeGrid) {
    ?>
        'containerstart':  '<?=addslashes($themeGridFramework->getPageThemeGridFrameworkContainerStartHTML())?>',
        'containerend': '<?=addslashes($themeGridFramework->getPageThemeGridFrameworkContainerEndHTML())?>',
		'rowstart':  '<?=addslashes($themeGridFramework->getPageThemeGridFrameworkRowStartHTML())?>',
		'rowend': '<?=addslashes($themeGridFramework->getPageThemeGridFrameworkRowEndHTML())?>',
        'additionalGridColumnClasses': '<?=$themeGridFramework->getPageThemeGridFrameworkColumnAdditionalClasses()?>',
        'additionalGridColumnOffsetClasses': '<?=$themeGridFramework->getPageThemeGridFrameworkColumnOffsetAdditionalClasses()?>',
		<?php if ($controller->getTask() == 'add') {
    ?>
		'maxcolumns': '<?=$controller->getAreaObject()->getAreaGridMaximumColumns()?>',
		<?php
} else {
    ?>
		'maxcolumns': '<?=$themeGridMaxColumns ?? ''?>',
		<?php
}
    ?>
		'gridColumnClasses': [
			<?php $classes = $themeGridFramework->getPageThemeGridFrameworkColumnClasses();
    ?>
			<?php for ($i = 0; $i < count($classes); $i++) {
    $class = $classes[$i];
    ?>
				'<?=$class?>' <?php if (($i + 1) < count($classes)) {
    ?>, <?php
}
    ?>

			<?php
}
    ?>
		]
		<?php
} ?>
	});

	$('#ccm-layouts-toolbar').parent().concreteBlockInlineStyleCustomizer();

});


</script>

<div class="ccm-area-layout-control-bar-wrapper">
	<div id="ccm-area-layout-active-control-bar" class="ccm-area-layout-control-bar ccm-area-layout-control-bar-<?=$controller->getTask()?>"></div>
</div>
