<?php

namespace BlueSpice\GroupManager\Hook\LoadExtensionSchemaUpdates;

use BlueSpice\Hook\LoadExtensionSchemaUpdates;

class AddCleanUpUserGroupTableMaintenanceScript extends LoadExtensionSchemaUpdates {
	/**
	 *
	 * @return bool
	 */
	protected function doProcess() {
		$this->updater->addPostDatabaseUpdateMaintenance(
			'CleanUpUserGroupTable'
		);
		return true;
	}

}
