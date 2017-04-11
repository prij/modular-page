<?php

class Module extends DataObject
{
	private static $extensions = [
		Versioned::class,
	];

	private static $db = [
		'Title' => 'Varchar',
		'Content' => 'HTMLText',
	];

	private static $has_one = [
		'Parent' => 'SiteTree'
	];

	public function getCMSFields()
	{
		return new FieldList(
			new TextField('Title'),
			new HtmlEditorField('Content')
		);
	}

	/**
	 * Returns list of all fields that should be added to the full text index
	 *
	 * @return array
	 */
	public function getSearchableContent()
	{
		return [
			$this->Title,
			$this->Content
		];
	}
}
