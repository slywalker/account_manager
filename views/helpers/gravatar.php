<?php
class GravatarHelper extends AppHelper {

	public function url($email, $size = 20) {
		$grav_url = 'http://www.gravatar.com/avatar.php?gravatar_id='.md5(strtolower($email)).'&size='.$size;
		return $grav_url;
	}
}
?>