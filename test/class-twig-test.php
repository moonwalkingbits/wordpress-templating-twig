<?php

namespace Moonwalking_Bits\Templating\Engine;

use Moonwalking_Bits\Templating\Template_Not_Found_Exception;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Twig\Environment;
use Twig\Loader\ArrayLoader;
use Twig\Loader\FilesystemLoader;

/**
 * @coversDefaultClass \Moonwalking_Bits\Templating\Engine\Twig
 */
class Twig_Test extends TestCase {
	private array $template_directories = array(
		__DIR__ . '/fixtures/templates/'
	);
	private Twig $engine;

	/**
	 * @before
	 */
	public function set_up(): void {
		$this->engine = new Twig( new Environment( new FilesystemLoader( $this->template_directories ) ) );
	}

	/**
	 * @test
	 */
	public function should_throw_exception_if_template_not_found(): void {
		$this->expectException( Template_Not_Found_Exception::class );

		$this->engine->render( 'not-found' );
	}

	/**
	 * @test
	 */
	public function should_locate_template_by_name(): void {
		$this->assertEquals( 'title', $this->engine->render( 'index' ) );
	}

	/**
	 * @test
	 */
	public function should_locate_template_by_name_and_extension(): void {
		$this->assertEquals( 'title', $this->engine->render( 'index.twig' ) );
	}

	/**
	 * @test
	 */
	public function should_locate_template_in_sub_directory(): void {
		$this->assertEquals( 'title', $this->engine->render( 'sub/index' ) );
	}

	/**
	 * @test
	 */
	public function should_accept_template_directories(): void {
		$engine = new Twig( new Environment( new FilesystemLoader() ) );
		$engine->add_template_directories( $this->template_directories );

		$this->assertEquals( 'title', $engine->render( 'index.twig' ) );
	}

	/**
	 * @test
	 */
	public function should_throw_exception_if_not_using_filesystem_loader(): void {
		$this->expectException( RuntimeException::class );

		$engine = new Twig( new Environment( new ArrayLoader() ) );
		$engine->add_template_directories( array() );
	}

	/**
	 * @test
	 */
	public function should_render_template_in_given_context(): void {
		$context = array(
			'title' => 'context'
		);

		$this->assertEquals( 'context', $this->engine->render( 'index.twig', $context ) );
	}
}
