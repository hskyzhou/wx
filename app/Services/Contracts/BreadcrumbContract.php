<?php
	namespace App\Services\Contracts;

	interface BreadcrumbContract{
		public function getCurrentBreadcrumb();

		public function getCurrentActiveMenu();
	}