<?php

/**
 * @file plugins/importexport/native/filter/PKPAuthorNativeXmlFilter.inc.php
 *
 * Copyright (c) 2000-2013 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class PKPAuthorNativeXmlFilter
 * @ingroup plugins_importexport_native
 *
 * @brief Base class that converts a set of authors to a Native XML document
 */

import('lib.pkp.plugins.importexport.native.filter.NativeExportFilter');

class PKPAuthorNativeXmlFilter extends NativeExportFilter {
	/**
	 * Constructor
	 * @param $filterGroup FilterGroup
	 */
	function PKPAuthorNativeXmlFilter($filterGroup) {
		$this->setDisplayName('Native XML author export');
		parent::NativeExportFilter($filterGroup);
	}


	//
	// Implement template methods from PersistableFilter
	//
	/**
	 * @copydoc PersistableFilter::getClassName()
	 */
	function getClassName() {
		return 'lib.pkp.plugins.importexport.native.filter.PKPAuthorNativeXmlFilter';
	}


	//
	// Implement template methods from Filter
	//
	/**
	 * @see Filter::process()
	 * @param $authors array Array of authors
	 * @return DOMDocument
	 */
	function &process(&$authors) {
		// Create the XML document
		$doc = new DOMDocument('1.0');
		$deployment = $this->getDeployment();

		if (count($authors)==1) {
			// Only one submission specified; create root node
			$rootNode = $this->createPKPAuthorNode($doc, $authors[0]);
		} else {
			// Multiple authors; wrap in a <authors> element
			$rootNode = $doc->createElementNS($deployment->getNamespace(), 'authors');
			foreach ($authors as $author) {
				$rootNode->appendChild($this->createPKPAuthorNode($doc, $author));
			}
		}
		$doc->appendChild($rootNode);
		$rootNode->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
		$rootNode->setAttribute('xsi:schemaLocation', $deployment->getNamespace() . ' ' . $deployment->getSchemaFilename());

		return $doc;
	}

	//
	// PKPAuthor conversion functions
	//
	/**
	 * Create and return an author node.
	 * @param $doc DOMDocument
	 * @param $author PKPAuthor
	 * @return DOMElement
	 */
	function createPKPAuthorNode($doc, $author) {
		// Create the root node and namespace information
		$deployment = $this->getDeployment();
		$authorNode = $doc->createElementNS($deployment->getNamespace(), 'author');
		if ($author->getPrimaryContact()) $authorNode->setAttribute('primary_contact', 'true');

		$authorNode->appendChild($doc->createElementNS($deployment->getNamespace(), 'firstname', $author->getFirstName()));
		$this->createOptionalNode($doc, $authorNode, 'middlename', $author->getMiddleName());
		$authorNode->appendChild($doc->createElementNS($deployment->getNamespace(), 'lastname', $author->getLastName()));

		$this->createLocalizedNodes($doc, $authorNode, 'affiliation', $author->getAffiliation(null));

		$this->createOptionalNode($doc, $authorNode, 'country', $author->getCountry());
		$authorNode->appendChild($doc->createElementNS($deployment->getNamespace(), 'email', $author->getEmail()));
		$this->createOptionalNode($doc, $authorNode, 'url', $author->getUrl());

		$this->createLocalizedNodes($doc, $authorNode, 'biography', $author->getBiography(null));

		return $authorNode;
	}
}

?>