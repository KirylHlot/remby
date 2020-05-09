<?php

namespace Premmerce\Premmerce\Admin;

use  Behat\Transliterator\Transliterator ;
class Settings
{
    const  OPTIONS_KEY = 'premmerce_additional_settings' ;
    /**
     * @var array
     */
    private  $options ;
    /**
     * Register hooks
     */
    public function addActions()
    {
        add_action( 'admin_init', [ $this, 'initSettings' ] );
        add_filter( 'sanitize_title', function ( $title ) {
            if ( 'on' === $this->getOption( 'transliterate_slugs' ) ) {
                return Transliterator::utf8ToAscii( $title );
            }
            return $title;
        }, 9 );
    }
    
    /**
     * Init settings
     */
    public function initSettings()
    {
        register_setting( 'premmerce_settings', self::OPTIONS_KEY );
        add_settings_section(
            'slugs',
            __( 'Slugs', 'premmerce' ),
            '',
            'premmerce'
        );
        add_settings_field(
            'transliterate_slugs',
            '',
            [ $this, 'checkboxCallback' ],
            'premmerce',
            'slugs',
            [
            'label'     => __( 'Enable slugs transliteration', 'premmerce' ),
            'label_for' => 'transliterate_slugs',
        ]
        );
    }
    
    /**
     * @param array $args
     */
    public function checkBoxCallback( $args )
    {
        $checkbox = '<label ><input type="checkbox" name="premmerce_additional_settings[%s]" %s >%s</label>';
        $checked = $this->getOption( $args['label_for'] );
        printf(
            $checkbox,
            esc_attr( $args['label_for'] ),
            checked( 'on', $checked, false ),
            $args['label']
        );
    }
    
    /**
     * @param string $key
     * @param null $default
     *
     * @return mixed|null
     */
    private function getOption( $key, $default = null )
    {
        if ( is_null( $this->options ) ) {
            $this->options = get_option( self::OPTIONS_KEY );
        }
        return ( isset( $this->options[$key] ) ? $this->options[$key] : $default );
    }

}