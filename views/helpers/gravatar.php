<?php
class GravatarHelper extends AppHelper {

	public function url($email, $size = 20) {
		$grav_url = 'http://www.gravatar.com/avatar/'.md5(strtolower($email)).'?s='.$size;
		return $grav_url;
	}
}
?>