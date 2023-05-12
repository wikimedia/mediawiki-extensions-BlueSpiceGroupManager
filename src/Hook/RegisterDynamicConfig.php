<?php

namespace BlueSpice\GroupManager\Hook;

use BlueSpice\GroupManager\DynamicConfig\Groups;
use MWStake\MediaWiki\Component\DynamicConfig\Hook\MWStakeDynamicConfigRegisterConfigsHook;

class RegisterDynamicConfig implements MWStakeDynamicConfigRegisterConfigsHook {

	/**
	 * @inheritDoc
	 */
	public function onMWStakeDynamicConfigRegisterConfigs( array &$configs ): void {
		$configs[] = new Groups();
	}
}
