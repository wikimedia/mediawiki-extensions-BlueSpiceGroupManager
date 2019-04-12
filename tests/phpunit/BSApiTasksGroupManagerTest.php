<?php

use BlueSpice\Tests\BSApiTasksTestBase;

/**
 * @group medium
 * @group API
 * @group Database
 * @group BlueSpice
 * @group BlueSpiceGroupManager
 */
class BSApiTasksGroupManagerTest extends BSApiTasksTestBase {

	protected function getModuleName() {
		return 'bs-groupmanager';
	}

	public function getTokens() {
		return $this->getTokenList( self::$users[ 'sysop' ] );
	}

	public function testAddGroup() {
		global $wgAdditionalGroups;

		$groupsToAdd = [ 'DummyGroup', 'DummyGroup2', 'DummyGroup3' ];
		foreach ( $groupsToAdd as $sGroup ) {
			$data = $this->addGroup( $sGroup );
		}

		$this->assertTrue( $data->success );
		$this->assertTrue( isset( $wgAdditionalGroups['DummyGroup'] ) );
		$this->assertTrue( isset( $wgAdditionalGroups['DummyGroup2'] ) );
		$this->assertTrue( isset( $wgAdditionalGroups['DummyGroup3'] ) );
	}

	public function testEditGroup() {
		global $wgAdditionalGroups, $wgGroupPermissions;

		$wgGroupPermissions['DummyGroup'] = [];

		$data = $this->executeTask(
			'editGroup',
			[
				'group' => 'DummyGroup',
				'newGroup' => 'FakeGroup'
			]
		);

		$this->assertTrue( isset( $wgAdditionalGroups['FakeGroup'] ) );
		$this->assertTrue( $wgAdditionalGroups['FakeGroup'] );
		$this->assertFalse( $wgAdditionalGroups['DummyGroup'] );
	}

	public function testRemoveGroup() {
		global $wgAdditionalGroups;

		$data = $this->executeTask(
			'removeGroup',
			[
				'group' => 'FakeGroup'
			]
		);

		$this->assertTrue( $data->success );
		$this->assertFalse( $wgAdditionalGroups['FakeGroup'] );
	}

	public function testRemoveGroups() {
		global $wgAdditionalGroups;

		$data = $this->executeTask(
			'removeGroups',
			[
				'groups' => [ 'DummyGroup2', 'DummyGroup3' ]
			]
		);

		$this->assertTrue( $data->success );
		$this->assertFalse( $wgAdditionalGroups['DummyGroup2'] );
		$this->assertFalse( $wgAdditionalGroups['DummyGroup3'] );
	}

	protected function addGroup( $sName ) {
		$data = $this->executeTask(
			'addGroup',
			[
				'group' => $sName
			]
		);

		return $data;
	}
}
