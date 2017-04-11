<?php


class SiteTreeSearchExtension extends SiteTreeExtension
{
	public function onAfterPublish(&$original)
	{
		$fields = ['Title'];
		$blocks = [];
		foreach($fields as $field) {
			$blocks[] = $this->owner->$field;
		}

		if ($this->owner->hasExtension(ModularPageExtension::class)) {
			/** @var Module $module */
			foreach ($this->owner->Modules() as $module) {
				foreach ($module->getSearchableContent() as $moduleContent) {
					$blocks[] = $moduleContent;
				}
			}
		}

		$content = implode(' ', $blocks);

		// Write this to a dataobject
		$searchContainer = SiteTreeSearch::get()->filter('PageID', $this->owner->ID)->first();
		if (!$searchContainer) {
			$searchContainer = new SiteTreeSearch();
			$searchContainer->PageID = $this->owner->ID;
		}
		$searchContainer->Content = $content;
		$searchContainer->write();
	}

	/**
	 * @param SS_HTTPRequest $request
	 */
	public function doSearch($request)
	{
		$terms = $request->getVar('Search');

		$results = new SQLSelect('"PageID"', '"SiteTreeSearch"');
		$results->addWhere([
			'MATCH (Content) AGAINST (%s)' => $terms
		]);
		$pages = [];
		foreach ($results as $result) {
			$pages[] = SiteTree::get()->byID($result['PageID']);
		}
		return $pages;
	}
}
