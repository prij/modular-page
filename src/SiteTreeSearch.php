<?php


class SiteTreeSearch extends DataObject
{
	private static $has_one = [
		'Page' => SiteTree::class,
	];

	private static $indexes = [
		'SearchIndex' => [
			'type' => 'fulltext',
			'value' => '"Content"'
		],
	];


	private static $db = [
		'Content' => 'Text'
	];

	private static $create_table_options = [
		MySQLSchemaManager::ID => 'ENGINE=MyISAM'
	];

	public function getTitle()
	{
		return $this->Page()->getTitle();
	}

	public function Link()
	{
		return $this->Page()->Link();
	}
}
