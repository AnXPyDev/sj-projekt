<?php
class Action {
	public $arglist;
	public $callback;

	function __construct($arglist, $callback) {
		$this->arglist = $arglist;
		$this->callback = $callback;
	}

	function validate($args) {
		foreach ($this->arglist as $argname) {
			if (!isset($args[$argname])) {
				return false;
			}
		}

		return true;
	}

	function __invoke($args, &$result) {
		if (!$this->validate($args)) {
			$result['success'] = false;
			$result['error'] = "Arg validation failed";
			return;
		}

		$result['success'] = true;
		$callback = $this->callback;
		$callback($args, $result);
	}
}
?>
