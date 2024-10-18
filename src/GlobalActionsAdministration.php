<?php

namespace BlueSpice\GroupManager;

use Message;
use MWStake\MediaWiki\Component\CommonUserInterface\Component\RestrictedTextLink;
use SpecialPage;

class GlobalActionsAdministration extends RestrictedTextLink {

	/**
	 *
	 */
	public function __construct() {
		parent::__construct( [] );
	}

	/**
	 *
	 * @return string
	 */
	public function getId(): string {
		return 'ga-bs-groupmanager';
	}

	/**
	 *
	 * @return array
	 */
	public function getPermissions(): array {
		$permissions = [
			'groupmanager-viewspecialpage'
		];
		return $permissions;
	}

	/**
	 *
	 * @return string
	 */
	public function getHref(): string {
		$tool = SpecialPage::getTitleFor( 'GroupManager' );
		return $tool->getLocalURL();
	}

	/**
	 *
	 * @return Message
	 */
	public function getTitle(): Message {
		return Message::newFromKey( 'bs-groupmanager-desc' );
	}

	/**
	 *
	 * @return Message
	 */
	public function getText(): Message {
		return Message::newFromKey( 'bs-groupmanager-text' );
	}

	/**
	 *
	 * @return Message
	 */
	public function getAriaLabel(): Message {
		return Message::newFromKey( 'bs-groupmanager-text' );
	}
}
