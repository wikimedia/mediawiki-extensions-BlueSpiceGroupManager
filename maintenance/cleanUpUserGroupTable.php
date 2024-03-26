<?php

$IP = dirname( dirname( dirname( __DIR__ ) ) );
require_once "$IP/maintenance/Maintenance.php";

class CleanUpUserGroupTable extends LoggedUpdateMaintenance {

	/**
	 * @return bool
	 */
	protected function doDBUpdates() {
		$this->getUserIds();
		$this->getSysopIds();
		$this->deleteNotMatchingIds();
		$this->output( "All orphaned group assignments have been removed.\n" );
		return true;
	}

	/**
	 * @var array
	 */
	private $userIds = [];

	/**
	 * @return void
	 */
	private function getUserIds() {
		$dbr = $this->getDB( DB_REPLICA );
		$res = $dbr->select( 'user', 'user_id' );
		foreach ( $res as $row ) {
			$this->userIds[] = $row->user_id;
		}
	}

	/**
	 * @var array
	 */
	private $sysOpIds = [];

	/**
	 * @return void
	 */
	private function getSysopIds() {
		$dbr = $this->getDB( DB_REPLICA );
		$res = $dbr->select( 'user_groups', 'ug_user' );
		foreach ( $res as $row ) {
			$this->sysOpIds[] = $row->ug_user;
		}
	}

	/**
	 * @return void
	 */
	private function deleteNotMatchingIds() {
		$idsToRemoveFromGroupsTable = array_diff( $this->sysOpIds, $this->userIds );
		$dbw = $this->getDB( DB_PRIMARY );
		foreach ( $idsToRemoveFromGroupsTable as $idToRemoveFromGroupsTable ) {
			$this->output( "Clean up groups of non existing user with ID '$idToRemoveFromGroupsTable'\n" );
			$dbw->delete( 'user_groups', [ 'ug_user' => $idToRemoveFromGroupsTable ] );
		}
	}

	/**
	 * @return void
	 */
	protected function getUpdateKey() {
		return 'bluespice-group-manager-cleanUpUserGroupTable';
	}
}

$maintClass = CleanUpUserGroupTable::class;
require_once RUN_MAINTENANCE_IF_MAIN;
