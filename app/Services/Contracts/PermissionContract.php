<?php
	namespace App\Services\Contracts;

	interface PermissionContract{
		public function dealArrayToJsTreeAdd($permissions);
		public function dealArrayToJsTreeUpdate($permissions, $has_permissions);
	}