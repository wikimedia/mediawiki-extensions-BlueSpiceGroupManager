<?php

namespace BlueSpice\GroupManager\HookHandler;

use BlueSpice\GroupManager\GlobalActionsManager;
use MWStake\MediaWiki\Component\CommonUserInterface\Hook\MWStakeCommonUIRegisterSkinSlotComponents;

class Main implements MWStakeCommonUIRegisterSkinSlotComponents {

	/**
	 * @inheritDoc
	 */
	public function onMWStakeCommonUIRegisterSkinSlotComponents( $registry ): void {
		$registry->register(
			'GlobalActionsManager',
			[
				'ga-bs-groupmanager' => [
					'factory' => function () {
						return new GlobalActionsManager();
					}
				]
			]
		);
	}
}
