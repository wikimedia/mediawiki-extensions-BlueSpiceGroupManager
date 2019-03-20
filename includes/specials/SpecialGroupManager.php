<?php

/**
 * Special page for GroupManager of BlueSpice (MediaWiki)
 *
 * Part of BlueSpice MediaWiki
 *
 * @author     Leonid Verhovskij <verhovskij@hallowelt.com>
 * @package    BlueSpiceExtensions
 * @subpackage GroupManager
 * @copyright  Copyright (C) 2016 Hallo Welt! GmbH, All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GPL-3.0-only
 * @filesource
 */
class SpecialGroupManager extends \BlueSpice\SpecialPage {

	/**
	 *
	 */
	public function __construct() {
		parent::__construct( 'GroupManager', 'groupmanager-viewspecialpage' );
	}

	/**
	 *
	 * @param string $par URL parameters to special page.
	 */
	public function execute( $par ) {
		parent::execute( $par );
		$outputPage = $this->getOutput();

		$this->getOutput()->addModules( 'ext.bluespice.groupManager' );
		$outputPage->addHTML( '<div id="bs-groupmanager-grid" class="bs-manager-container"></div>' );
	}

}
