<?php

/**
 * Animations Helper
 *
 * @link       https://www.feataholic.com
 * @since      1.1.8 Free
 *
 * @package    MZLDR
 * @subpackage MZLDR/includes/helpers
 */

/**
 * Animations Helper
 *
 * @since      1.1.8 Free
 * @package    MZLDR
 * @subpackage MZLDR/includes/helpers
 * @author     Your Name <email@example.com>
 */
class MZLDR_Animations_Helper {

	public static function getAnimations() {
		return [
			[
				'name' => 'Attention Seekers',
				'items' => [
					'bounce',
					'flash',
					'pulse',
					'rubberBand',
					'shake',
					'swing',
					'tada',
					'wobble',
					'jello',
					'heartBeat'
				]
			],
			[
				'name' => 'Bouncing Entrances',
				'items' => [
					'bounceIn',
					'bounceInDown',
					'bounceInLeft',
					'bounceInRight',
					'bounceInUp'
				]
			],
			[
				'name' => 'Bouncing Exits',
				'items' => [
					'bounceOut',
					'bounceOutDown',
					'bounceOutLeft',
					'bounceOutRight',
					'bounceOutUp'
				]
			],
			[
				'name' => 'Fading Entrances',
				'items' => [
					'fadeIn',
					'fadeInDown',
					'fadeInDownBig',
					'fadeInLeft',
					'fadeInLeftBig',
					'fadeInRight',
					'fadeInRightBig',
					'fadeInUp',
					'fadeInUpBig'
				]
			],
			[
				'name' => 'Fading Exits',
				'items' => [
					'fadeOut',
					'fadeOutDown',
					'fadeOutDownBig',
					'fadeOutLeft',
					'fadeOutLeftBig',
					'fadeOutRight',
					'fadeOutRightBig',
					'fadeOutUp',
					'fadeOutUpBig'
				]
			],
			[
				'name' => 'Flippers',
				'items' => [
					'flip',
					'flipInX',
					'flipInY',
					'flipOutX',
					'flipOutY'
				]
			],
			[
				'name' => 'Lightspeed',
				'items' => [
					'lightSpeedIn',
					'lightSpeedOut'
				]
			],
			[
				'name' => 'Rotating Entrances',
				'items' => [
					'rotateIn',
					'rotateInDownLeft',
					'rotateInDownRight',
					'rotateInUpLeft',
					'rotateInUpRight'
				]
			],
			[
				'name' => 'Rotating Exits',
				'items' => [
					'rotateOut',
					'rotateOutDownLeft',
					'rotateOutDownRight',
					'rotateOutUpLeft',
					'rotateOutUpRight'
				]
			],
			[
				'name' => 'Sliding Entrances',
				'items' => [
					'slideInUp',
					'slideInDown',
					'slideInLeft',
					'slideInRight'
				]
			],
			[
				'name' => 'Sliding Exits',
				'items' => [
					'slideOutUp',
					'slideOutDown',
					'slideOutLeft',
					'slideOutRight'
				]
			],
			[
				'name' => 'Zoom Entrances',
				'items' => [
					'zoomIn',
					'zoomInDown',
					'zoomInLeft',
					'zoomInRight',
					'zoomInUp'
				]
			],
			[
				'name' => 'Zoom Exits',
				'items' => [
					'zoomOut',
					'zoomOutDown',
					'zoomOutLeft',
					'zoomOutRight',
					'zoomOutUp'
				]
			],
			[
				'name' => 'Specials',
				'items' => [
					'hinge',
					'jackInTheBox',
					'rollIn',
					'rollOut'
				]
			],
		];
	}

	public static function getAnimationValues() {
		$values = [];

		foreach (self::getAnimations() as $anim) {
			foreach ($anim['items'] as $a) {
				$values[] = $a;
			}
		}
		
		return $values;
	}

}