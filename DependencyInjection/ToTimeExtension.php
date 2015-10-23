<?php
namespace Org\CoreBundle\DependencyInjection;

use Symfony\Component\HttpKernel\KernelInterface;

class ToTimeExtension extends \Twig_Extension {
	/**
	 * {@inheritdoc}
	 */
	public function getFunctions() {
		return array(
				'totime' => new \Twig_Function_Method($this, 'toTime')
		);
	}

	/**
	 * @param string $string
	 * @return int
	 */
	public function toTime ($string) {
		return strtotime($string);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getName() {
		return 'timer';
	}
}