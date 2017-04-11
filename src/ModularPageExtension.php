<?php

/**
 * Class ModularPageExtension
 */
class ModularPageExtension extends SiteTreeExtension
{
	private static $has_many = [
		'Modules' => Module::class,
	];

	public function updateCMSFields(FieldList $fields)
	{
		$config = GridFieldConfig_RecordEditor::create();
		$editor = new GridField('Modules', 'Modules', $this->owner->Modules(), $config);
		$fields->addFieldToTab('Root.Modules', $editor);
		return $fields;
	}

	public function onAfterRollback($version) {
		$oldVersion = Versioned::get_version(SiteTree::class, $this->owner->ID, $version);
		$date = $oldVersion->LastEdited;

		// Todo: Get all Module instances with:
		// - LastEdited >= $date
		// - PageID === $this->owner->ID
		// then revert these to the Modules relationship
		// and delete any modules that are currently in Modules, but not the reverted set
		$allIDs = new SQLSelect('"RecordID"', '"Module_versions"', ['"PageID" = ?' => $this->owner->ID]);

	}

	public function onAfterPublish(&$original)
	{
		foreach ($this->owner->Modules() as $module) {
			$module->publish("Stage", "Live");
		}

		// todo: Remove any modules from live that are NOT on stage
	}
}
