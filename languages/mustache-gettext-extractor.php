<?php declare(strict_types=1);

class SIW_Mustache_Gettext_Extractor {
	//phpcs:disable
	private const MUSTACHE_TAG_REGEX = '/{{\s*.*?\s*}}/';
	private const I18N_TAG_REGEX = '/{{#__}}(.*?){{\/__}}/';
	private const TEXTDOMAIN = 'siw';

	public function __construct( protected string $path, protected string $stubs_file ) {}

	protected function get_files(): \Generator {
		if ( ! is_dir( $this->path ) ) {
			throw new \RuntimeException( "{$this->path} is not a directory" );
		}

		$iterator = new \RecursiveDirectoryIterator( $this->path );
		$iterator = new \RecursiveIteratorIterator( $iterator );
		$iterator = new \RegexIterator( $iterator, '/\.mustache/', \RegexIterator::MATCH );

		yield from $iterator;
	}

	public function extract() {
		$gettext_stubs = [
			'<?php declare(strict_types=1);',
			'//phpcs:disable',
			'die();'
		];

		foreach ( $this->get_files() as $file ) {
			$contents = file_get_contents( $file->getPathname() );
			preg_match_all( self::I18N_TAG_REGEX, $contents, $matches );
			if ( empty( $matches[1] ) ) {
				continue;
			}
			$gettext_stubs[] = '';
			$gettext_stubs[] = '//file: ' . str_replace( $this->path, '', $file->getPathname());
			foreach ( $matches[1] as $match ) {
				$value = trim( $match );
				$value = preg_replace( self::MUSTACHE_TAG_REGEX, '%s', $value );
				$gettext_stubs[] = sprintf( "__( '%s', '%s' );", $value, self::TEXTDOMAIN );
			}
		}

		file_put_contents(
			$this->stubs_file,
			implode( PHP_EOL, $gettext_stubs )
		);
	}
}

( new SIW_Mustache_Gettext_Extractor( __DIR__ . '/../templates/mustache', __DIR__ . '/mustache-stubs.php' ) )->extract();
