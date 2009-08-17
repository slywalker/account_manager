<?php
/**
 * gravatar.php
 *
 * @package AccountManager
 * @author Yasuo Harada
 * @copyright 2009 SLYWALKER Co,.Ltd.
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 * @date $LastChangedDate$
 * @version $Rev$
 **/

/**
 * GravatarHelper
 **/
class GravatarHelper extends AppHelper {

	public function url($email, $size = 20) {
		$grav_url = 'http://www.gravatar.com/avatar/'.md5(strtolower($email)).'?s='.$size;
		return $grav_url;
	}
}
?>