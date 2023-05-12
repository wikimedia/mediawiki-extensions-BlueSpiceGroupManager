<?php

/**
 * GroupManager Extension for BlueSpice
 *
 * Administration interface for adding, editing and deleting usergroups.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, version 3.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 *
 * This file is part of BlueSpice MediaWiki
 * For further information visit https://bluespice.com
 *
 * @author     Sebastian Ulbricht <sebastian.ulbricht@dragon-design.hk>
 * @author     Markus Glaser <glaser@hallowelt.com>
 * @package    BlueSpice_Extensions
 * @subpackage GroupManager
 * @copyright  Copyright (C) 2018 Hallo Welt! GmbH, All rights reserved.
 * @license    http://www.gnu.org/copyleft/gpl.html GPL-3.0-only
 * @filesource
 */

namespace BlueSpice\GroupManager;

use CommentStoreComment;
use Exception;
use MediaWiki\Logger\LoggerFactory;
use MediaWiki\MediaWikiServices;
use MediaWiki\Revision\SlotRecord;
use MWStake\MediaWiki\Component\DynamicConfig\DynamicConfigManager;

class Extension extends \BlueSpice\Extension {

	/**
	 * saves all groupspecific data to a config file
	 *
	 * @param array|null $additionalGroups
	 *
	 * @return array the json answer
	 * @throws Exception
	 */
	public static function saveData( ?array $additionalGroups = null ) {
		$additionalGroups = $additionalGroups ?? $GLOBALS['wgAdditionalGroups'];
		foreach ( $additionalGroups as $group => $value ) {
			$nameErrors = self::getNameErrors( $group );
			if ( !empty( $nameErrors ) ) {
				return $nameErrors;
			} else {
				if ( $value !== false ) {
					self::checkI18N( $group );
				} else {
					self::checkI18N( $group, $value );
				}
			}
		}

		/** @var DynamicConfigManager $configManager */
		$configManager = MediaWikiServices::getInstance()->getService( 'MWStakeDynamicConfigManager' );
		$config = $configManager->getConfigObject( 'bs-groupmanager-groups' );
		$configManager->storeConfig( $config, $additionalGroups );

		return [
			'success' => true,
			'message' => \wfMessage( 'bs-groupmanager-grpadded' )->plain()
		];
	}

	/**
	 *
	 * @param string $name
	 * @return array
	 */
	public static function getNameErrors( $name ) {
		$invalidChars = [];
		$name = trim( $name );
		if ( substr_count( $name, '\'' ) > 0 ) {
			$invalidChars[] = '\'';
		}
		if ( substr_count( $name, '"' ) > 0 ) {
			$invalidChars[] = '"';
		}
		if ( !empty( $invalidChars ) ) {
			return [
				'success' => false,
				'message' => \wfMessage( 'bs-groupmanager-invalid-name' )
					->numParams( count( $invalidChars ) )
					->params( implode( ',', $invalidChars ) )
					->text()
			];
		} elseif ( preg_match( "/^[0-9]+$/", $name ) ) {
			return [
				'success' => false,
				'message' => \wfMessage( 'bs-groupmanager-invalid-name-numeric' )->plain()
			];
		} elseif ( strlen( $name ) > 255 ) {
			return [
				'success' => false,
				'message' => \wfMessage( 'bs-groupmanager-invalid-name-length' )->plain()
			];
		}
		return [];
	}

	/**
	 *
	 * @param string $group
	 * @param bool $value
	 */
	public static function checkI18N( $group, $value = true ) {
		$title = \Title::newFromText( 'group-' . $group, NS_MEDIAWIKI );
		$user = \RequestContext::getMain()->getUser();
		$services = MediaWikiServices::getInstance();
		if ( $value === false ) {
			if ( $title->exists() ) {
				$wikiPage = $services->getWikiPageFactory()->newFromTitle( $title );
				$deletePage = $services->getDeletePageFactory()->newDeletePage( $wikiPage, $user );
				$deletePage->deleteIfAllowed( 'Group does not exist anymore' );
			}
		} else {
			if ( !$title->exists() ) {
				$wikiPage = $services->getWikiPageFactory()->newFromTitle( $title );
				$updater = $wikiPage->newPageUpdater( $user );
				$content = $wikiPage->getContentHandler()->makeContent( $group, $title );
				$updater->setContent( SlotRecord::MAIN, $content );
				$comment = CommentStoreComment::newUnsavedComment( '' );
				try {
					$updater->saveRevision( $comment, EDIT_NEW );
				} catch ( Exception $e ) {
					$logger = LoggerFactory::getInstance( 'BlueSpiceGroupManager' );
					$logger->error( $e->getMessage() );
				}
			}
		}
	}
}
