<?php

namespace BlueSpice\GroupManager\Hook\LoadExtensionSchemaUpdates;

use BlueSpice\GroupManager\Maintenance\MigrateGmSettings as MaintenanceScript;
use BlueSpice\Hook\LoadExtensionSchemaUpdates;

class MigrateGmSettings extends LoadExtensionSchemaUpdates {
	/**
	 *
	 * @return bool
	 */
	protected function doProcess() {
		$this->updater->addPostDatabaseUpdateMaintenance( MaintenanceScript::class );
	}

}
