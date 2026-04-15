<?php

namespace App\Http\Helpers;

class SidebarHelper
{
	public static function sidebar($module, $menu)
	{
		// $data = Cache::get('admin_sidebar');
		// if(!$data){
		$data = self::initSidebar($module, $menu);
		CacheHelper::getCacheData('admin_sidebar', $data);
		// }
		return $data;
	}
	/**
	 *
	 */
	public static function initSidebar($module, $menu)
	{
		$htmls = '';
		$admin = 'admin';
		if (isset($menu['type']) && $menu['type'] == 'menu-group') {
			if ($menu['show']) {
				$htmls .= '<li class="menu-title">' . __($menu['name']) . '</li>';
			}
			if (isset($menu['children']) && !empty($menu['children'])) {
				foreach ($menu['children'] as $keyMenu => $valueMenu) {
					$htmls .= self::generateSidebar($keyMenu, $valueMenu);
				}
			} else {
				$htmls .= self::generateSidebar($module, $menu);
			}
		} else {
			$htmls .= self::generateSidebar($module, $menu);
		}
		return $htmls;
	}
	/**
	 *
	 */
	public static function generateSidebar($module, $menu)
	{
		return self::renderMenuItem($module, $menu);
	}

	private static function renderMenuItem($module, $menu, $parentKey = '')
	{
		$admin = 'admin';
		$html = '';

		$hasChildren = isset($menu['children']) && !empty($menu['children']);

		$url = $parentKey
			? url("$admin/$module/$parentKey")
			: url("$admin/$module");

		$html .= '<li>';
		$html .= '<a href="' . ($hasChildren ? 'javascript:void(0);' : $url) . '" class="' . ($hasChildren ? 'has-arrow waves-effect' : 'waves-effect') . '">';
		$html .= '<i class="' . ($menu['icon'] ?? '') . '"></i>';
		$html .= '<span>' . __($menu['name']) . '</span>';
		$html .= '</a>';

		if ($hasChildren) {
			$html .= '<ul class="sub-menu" aria-expanded="true">';

			foreach ($menu['children'] as $key => $child) {
				$childKey = $parentKey ? "$parentKey/$key" : $key;
				$html .= self::renderMenuItem($module, $child, $childKey);
			}

			$html .= '</ul>';
		}

		$html .= '</li>';

		return $html;
	}
}
