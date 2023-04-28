<?php
class Action {
	public $arglist;
	public $callback;
	public $validate;

	function __construct($arglist, $callback, $validate = NULL) {
		$this->arglist = $arglist;
		$this->callback = $callback;
		$this->validate = $validate;
	}

	function validate($args) {
		foreach ($this->arglist as $argname) {
			if (!isset($args[$argname])) {
				return false;
			}
		}

		if (isset($this->validate) && !$this->validate($args)) {
			return false;
		}

		return true;
	}

	function __invoke($args, &$result) {
		if (!$this->validate($args)) {
			$result['success'] = false;
			return;
		}

		$result['success'] = true;
		$callback = $this->callback;
		$callback($args, $result);
	}
}
?>
