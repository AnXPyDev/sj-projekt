<?php
class Pager {
	private $url;
	private $count;
	private $current;
	private $per_page;
	private $buttons;

	function __construct($url, $current, $count, $per_page, $buttons) {
		$this->url = $url;
		$this->current = $current;
		$this->count = $count;
		$this->per_page = $per_page;
		$this->buttons = $buttons;
	}

	function make_html() {
		$min_page = $this->current - floor($this->buttons / 2);
		if ($min_page < 1) {
			$min_page = 1;
		}
		$max_page = ceil($this->count / $this->per_page);
		if ($max_page < 1) {
			$max_page = 1;
		}
		$end_page = $min_page + $this->buttons;
		if ($max_page < $end_page) {
			$end_page = $max_page;
		}

		$html = <<<END
				<a href="{$this->url}1"><i class="fas fa-angle-double-left"></i></a>
END;

		for ($i = $min_page; $i <= $end_page; $i++) {
			$current_class = $i == $this->current ? 'class="current" ' : "";
			$html = $html.<<<END
				<a {$current_class}href="{$this->url}{$i}">{$i}</a>
END;
		}

		$html = $html.<<<END
				<a href="{$this->url}{$max_page}"><i class="fas fa-angle-double-right"></i></a>
END;
		return $html;
	}
}
?>
