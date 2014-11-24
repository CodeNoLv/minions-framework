<?php
	Class indexController extends controller
	{
		public static function action_index()
		{
			$contentVariable = "This is content";

			$view = new view();
			$view->setBlock("content", "sample/index");
			$view->setValue("contentVariable", $contentVariable);
			$view->setValue("title", "Main page");
			$view->output();
		}

		public static function action_otherpage()
		{
			$contentVariable = "This is other pages content";

			$view = new view();
			$view->setBlock("content", "sample/otherpage");
			$view->setValue("contentVariable", $contentVariable);
			$view->setValue("title", "Other page");
			$view->output();
		}
	}
?>