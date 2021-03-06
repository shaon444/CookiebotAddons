<?php

namespace cookiebot_addons_framework\lib;

class Settings_Service implements Settings_Service_Interface {

	/**
	 * @var \DI\Container
	 */
	public $container;

	CONST OPTION_NAME = 'cookiebot_available_addons';

	/**
	 * Settings_Service constructor.
	 *
	 * @param $container
	 *
	 * @since 1.3.0
	 */
	public function __construct( $container ) {
		$this->container = $container;
	}

	/**
	 * Returns true if the addon is enabled in the backend
	 *
	 * @param $addon
	 *
	 * @return mixed
	 *
	 * @since 1.3.0
	 */
	public function is_addon_enabled( $addon ) {
		$option = get_option( static::OPTION_NAME );

		if ( isset( $option[ $addon ]['enabled'] ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Returns true if the addon is installed
	 *
	 * @param $addon
	 *
	 * @return int|\WP_Error
	 *
	 * @since 1.3.0
	 */
	public function is_addon_installed( $addon ) {
		return ( $addon !== false && is_wp_error( validate_plugin( $addon ) ) ) ? false : true;
	}

	/**
	 * Returns true if the addon plugin is activated
	 *
	 * @param $addon
	 *
	 * @return bool
	 *
	 * @since 1.3.0
	 */
	public function is_addon_activated( $addon ) {
		return ( $addon === false || is_plugin_active( $addon ) ) ? true : false;
	}

	/**
	 * Returns all cookie type for given addon
	 *
	 * @param $addon    string  option name
	 * @param $default  array   default cookie types
	 *
	 * @return array
	 *
	 * @since 1.3.0
	 */
	public function get_cookie_types( $addon, $default = array() ) {
		$option = get_option( static::OPTION_NAME );

		if ( isset( $option[ $addon ]['cookie_type'] ) && is_array( $option[ $addon ]['cookie_type'] ) ) {
			return $option[ $addon ]['cookie_type'];
		}

		return $default;
	}

	/**
	 * Returns addons one by one through a generator
	 *
	 * @return array
	 * @throws \DI\DependencyException
	 * @throws \DI\NotFoundException
	 *
	 * @since 1.3.0
	 */
	public function get_addons() {
		$addons = array();

		foreach ( $this->container->get( 'plugins' ) as $addon ) {
			$addons[] = $this->container->get( $addon->class );
		}

		return $addons;
	}

	/**
	 * Returns active addons
	 *
	 * @return array
	 * @throws \DI\DependencyException
	 * @throws \DI\NotFoundException
	 *
	 * @since 1.3.0
	 */
	public function get_active_addons() {
		$active_addons = array();

		foreach ( $this->get_addons() as $addon ) {
			/**
			 * Load addon code if the plugin is active and addon is activated
			 */
			if ( $addon->is_addon_enabled() && $addon->is_addon_installed() && $addon->is_addon_activated() ) {
				$active_addons[] = $addon;
			}
		}

		return $active_addons;
	}

	/**
	 * Returns widget cookie types
	 *
	 * @param $option_key
	 * @param $widget
	 * @param array $default
	 *
	 * @return array
	 *
	 * @since 1.3.0
	 */
	public function get_widget_cookie_types( $option_key, $widget, $default = array() ) {
		$option = get_option( $option_key );

		if ( isset( $option[ $widget ]['cookie_type'] ) && is_array( $option[ $widget ]['cookie_type'] ) ) {
			return $option[ $widget ]['cookie_type'];
		}

		return $default;
	}

	/**
	 * Is widget enabled
	 *
	 * @param $option_key
	 * @param $widget
	 *
	 * @return bool
	 */
	public function is_widget_enabled( $option_key, $widget ) {
		$option = get_option( $option_key );

		if ( isset( $option[ $widget ] ) && ! isset( $option[ $widget ]['enabled'] ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Is placeholder enabled for a widget
	 *
	 * @param $option_key
	 * @param $widget
	 *
	 * @return bool
	 */
	public function is_widget_placeholder_enabled( $option_key, $widget ) {
		$option = get_option( $option_key );

		if ( isset( $option[ $widget ] ) && ! isset( $option[ $widget ]['placeholder']['enabled'] ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Checks if addon has placeholders
	 *
	 * @param $option_key
	 *
	 * @return bool
	 *
	 * @since 1.8.0
	 */
	public function widget_has_placeholder( $option_key, $widget_key ) {
		$option = get_option( $option_key );

		if ( isset( $option[ $widget_key ]['placeholder']['languages'] ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Returns widget placeholders
	 *
	 * @param $option_key
	 * @param $widget_key
	 *
	 * @return bool
	 *
	 * @since 1.8.0
	 */
	public function get_widget_placeholders( $option_key, $widget_key ) {
		$option = get_option( $option_key );

		if ( isset( $option[ $widget_key ]['placeholder']['languages'] ) ) {
			return $option[ $widget_key ]['placeholder']['languages'];
		}

		return false;
	}

	/**
	 * Returns all placeholders
	 *
	 * @param $option_key
	 *
	 * @return bool
	 *
	 * @since 1.8.0
	 */
	public function get_placeholders( $option_key ) {
		$option = get_option( static::OPTION_NAME );

		if ( isset( $option[ $option_key ]['placeholder']['languages'] ) ) {
			return $option[ $option_key ]['placeholder']['languages'];
		}

		return false;
	}

	/**
	 * Checks if addon has placeholders
	 *
	 * @param $option_key
	 *
	 * @return bool
	 *
	 * @since 1.8.0
	 */
	public function has_placeholder( $option_key ) {
		$option = get_option( static::OPTION_NAME );

		if ( isset( $option[ $option_key ]['placeholder']['languages'] ) ) {
			return true;
		}

		return false;
	}

	/**
	 * returns true if the addon placeholder is enabled
	 *
	 * @param $option_key
	 *
	 * @return bool
	 *
	 * @since 1.8.0
	 */
	public function is_placeholder_enabled( $option_key ) {
		$option = get_option( static::OPTION_NAME );

		if ( isset( $option[ $option_key ]['placeholder']['enabled'] ) ) {
			return true;
		}

		return false;
	}

	/**
	 * returns the placeholder if it does exist
	 *
	 * @param $option_key
	 * @param $default_placeholder
	 * @param $cookies
	 *
	 * @return bool|mixed
	 *
	 * @since 1.8.0
	 */
	public function get_placeholder( $option_key, $default_placeholder, $cookies ) {
		$option = get_option( static::OPTION_NAME );

		if ( isset( $option[ $option_key ]['placeholder']['enabled'] ) ) {
			if ( function_exists( 'cookiebot' ) ) {
				$cookiebot   = cookiebot();
				$currentLang = $cookiebot->get_language( true );

				// get current lang text
				if ( isset( $option[ $option_key ]['placeholder'][ $currentLang ] ) ) {
					return $this->placeholder_merge_tag( $option[ $option_key ]['placeholder'][ $currentLang ], $cookies );
				} else {
					return $this->placeholder_merge_tag( $default_placeholder, $cookies );
				}
			}
		}

		return false;
	}

	/**
	 * returns the placeholder if it does exist
	 *
	 * @param $option_key
	 * @param $default_placeholder
	 * @param $cookies
	 *
	 * @return bool|mixed
	 *
	 * @since 1.8.0
	 */
	public function get_widget_placeholder( $option_key, $widget_key, $default_placeholder, $cookies = '') {
		$option = get_option( $option_key );

		if ( isset( $option[ $widget_key ]['placeholder']['enabled'] ) ) {
			if ( function_exists( 'cookiebot' ) ) {
				$cookiebot   = cookiebot();
				$currentLang = $cookiebot->get_language( true );

				// get current lang text
				if ( isset( $option[ $widget_key ]['placeholder'][ $currentLang ] ) ) {
					return $this->placeholder_merge_tag( $option[ $widget_key ]['placeholder'][ $currentLang ], $cookies );
				} else {
					return $this->placeholder_merge_tag( $default_placeholder, $cookies );
				}
			}
		}

		return false;
	}

	/**
	 * Merges placeholder tags with values
	 *
	 * @param $placeholder
	 * @param $cookies
	 *
	 * @return mixed
	 *
	 * @since 1.8.0
	 */
	private function placeholder_merge_tag( $placeholder, $cookies ) {
		if ( strpos( $placeholder, '%s' ) !== false ) {
			$placeholder = str_replace( '%s', $cookies, $placeholder );
		}

		if ( strpos( $placeholder, '[renew_consent]' ) !== false ) {
			$placeholder = str_replace( '[renew_consent]', '<a href="javascript:Cookiebot.renew()">', $placeholder );
		}

		if ( strpos( $placeholder, '[/renew_consent]' ) !== false ) {
			$placeholder = str_replace( '[/renew_consent]', '</a>', $placeholder );
		}

		return $placeholder;
	}
}
