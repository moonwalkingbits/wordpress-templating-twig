<?php
/**
 * Templating Twig: Template engine implementation
 *
 * @package Moonwalking_Bits\Templating\Twig
 * @author Martin Pettersson
 * @license GPL-2.0
 * @since 0.1.0
 */

namespace Moonwalking_Bits\Templating\Twig;

use Moonwalking_Bits\Templating\Template_Engine_Interface;
use Moonwalking_Bits\Templating\Template_Not_Found_Exception;
use RuntimeException;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Loader\FilesystemLoader;

/**
 * Twig implementation of a template engine.
 *
 * @since 0.1.0
 */
class Template_Engine implements Template_Engine_Interface {

	/**
	 * A configured Twig environment.
	 *
	 * @var \Twig\Environment
	 */
	private Environment $environment;

	/**
	 * Creates a new template engine instance.
	 *
	 * @since 0.1.0
	 * @param \Twig\Environment $environment A configured Twig environment.
	 */
	public function __construct( Environment $environment ) {
		$this->environment = $environment;
	}

	/**
	 * Adds the given list of directories to be searched for templates.
	 *
	 * @since 0.1.0
	 * @param string[] $directories Directories to add to search path.
	 * @throws \RuntimeException If environment is not using a filesystem loader.
	 */
	public function add_template_directories( array $directories ): void {
		/**
		 * Twig filesystem loader instance.
		 *
		 * @var \Twig\Loader\FilesystemLoader
		 */
		$loader = $this->environment->getLoader();

		if ( ! $loader instanceof FilesystemLoader ) {
			throw new RuntimeException( 'Twig environment must use ' . FilesystemLoader::class );
		}

		foreach ( $directories as $directory ) {
			$loader->addPath( $directory );
		}
	}

	/**
	 * Renders a matching template in the given context.
	 *
	 * @since 0.1.0
	 * @param string $template_name Name of the template to render.
	 * @param array  $context Context to render the template in.
	 * @return string Rendered template result.
	 * @throws \Moonwalking_Bits\Templating\Template_Not_Found_Exception When the given template cannot be found.
	 */
	public function render( string $template_name, array $context = array() ): string {
		try {
			return $this->environment->render( $this->get_template_filename( $template_name ), $context );
		} catch ( LoaderError $_ ) {
			throw new Template_Not_Found_Exception( $template_name );
		}
	}

	/**
	 * Returns the filename of the given template name.
	 *
	 * @param string $template_name Name of the template.
	 * @return string Name of the template file.
	 */
	private function get_template_filename( string $template_name ): string {
		return false === strrpos( $template_name, '.twig', strlen( $template_name ) - 5 ) ?
			$template_name . '.twig' :
			$template_name;
	}
}
