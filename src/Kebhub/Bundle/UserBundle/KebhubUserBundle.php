<?php

namespace Kebhub\Bundle\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class KebhubUserBundle extends Bundle
{
	public function getParent()
	{
		return 'FOSUserBundle';
	}
}
