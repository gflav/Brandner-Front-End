<?php
$php_minimum = '5.1'; // User's PHP must be equal or newer to this version.

if ( version_compare( PHP_VERSION, $php_minimum ) < 0 ) {
	die( 'ERROR #9013. See <a href="http://ithemes.com/codex/page/BackupBuddy:_Error_Codes#9013">this codex page for details</a>. Sorry! PHP version ' . $php_minimum . ' or newer is required for BackupBuddy to properly run. You are running PHP version ' . PHP_VERSION . '.' );
}

define( 'ABSPATH', dirname( __FILE__ ) . '/' );
define( 'PB_BB_VERSION', '#VERSION#' );
define( 'PB_PASSWORD', '#PASSWORD#' );

// Unpack serverbuddy files into serverbuddy directory.
if ( !file_exists( ABSPATH . 'serverbuddy' ) || ( ( count( $_GET ) == 0 ) && ( count( $_POST ) == 0 ) ) ) {
	if ( file_exists( ABSPATH . 'serverbuddy' ) ) { // Delete serverbuddy directory and unpack fresh copy.
		echo '<!-- unlinking existing serverbuddy directory. -->';
		recursive_unlink( ABSPATH . 'serverbuddy' );
	}
	unpack_serverbuddy();
}



date_default_timezone_set( @date_default_timezone_get() ); // Prevents date() from throwing a warning if the default timezone has not been set.



if ( !file_exists( ABSPATH . 'serverbuddy/init.php' ) ) {
	die( 'Error: Unable to find file `' . ABSPATH . 'serverbuddy/init.php`. Make sure that you downloaded this script from within BackupBuddy. Copying files from inside the plugin directory is not sufficient as many file additions are made on demand.' );
} else {
	require_once( ABSPATH . 'serverbuddy/init.php' );
}



function recursive_unlink( $path ) {
  return is_file($path)?
    @unlink($path):
    array_map('recursive_unlink',glob($path.'/*'))==@rmdir($path);
}



/**
*	unpack_serverbuddy()
*
*	Unpacks required files encoded in serverbuddy.php into stand-alone files.
*
*	@return		null
*/
function unpack_serverbuddy() {
	if ( !is_writable( ABSPATH ) ) {
		echo 'Error #224834b. This directory is not write enabled. Please verify write permissions to continue.';
		die();
	} else {
		$unpack_file = '';
		
		// Make sure the file is complete and contains all the packed data to the end.
		if ( false === strpos( file_get_contents( ABSPATH . 'serverbuddy.php' ), '###PACKDATA' . ',END' ) ) { // Concat here so we don't false positive on this line when searching.
			die( 'ERROR: It appears your serverbuddy.php file is incomplete.  It may have not finished uploaded completely.  Please try re-downloading the script from within BackupBuddy in WordPress (do not just copy the file from the plugin directory) and re-uploading it.' );
		}
		
		$handle = @fopen( ABSPATH . 'serverbuddy.php', 'r' );
		if ( $handle ) {
			while ( ( $buffer = fgets( $handle ) ) !== false ) {
				if ( substr( $buffer, 0, 11 ) == '###PACKDATA' ) {
					$packdata_commands = explode( ',', trim( $buffer ) );
					array_shift( $packdata_commands );
					
					if ( $packdata_commands[0] == 'BEGIN' ) {
						// Start packed data.
					} elseif ( $packdata_commands[0] == 'FILE_START' ) {
						$unpack_file = $packdata_commands[2];
					} elseif ( $packdata_commands[0] == 'FILE_END' ) {
						$unpack_file = '';
					} elseif ( $packdata_commands[0] == 'END' ) {
						return;
					}
				} else {
					if ( $unpack_file != '' ) {
						if ( !is_dir( dirname( ABSPATH . $unpack_file ) ) ) {
							$mkdir_result = mkdir( dirname( ABSPATH . $unpack_file ), 0777, true ); // second param makes recursive.
							if ( $mkdir_result === false ) {
								echo 'Error #54455. Unable to mkdir `' . dirname( ABSPATH . $unpack_file ) . '`<br>';
							}
						}
						$fileput_result = file_put_contents( ABSPATH . $unpack_file, trim( base64_decode( $buffer ) ) );
						if ( $fileput_result === false ) {
							echo 'Error #65656. Unable to put file contents to `' . ABSPATH . $unpack_file . '`.<br>';
						}
					}
				}
			}
			if ( !feof( $handle ) ) {
				echo "Error: unexpected fgets() fail.<br>";
			}
			fclose( $handle );
		} else {
			echo 'ERROR #54455b: Unable to open serverbuddy.php file for reading in packaged data.<br>';
		}
	}
}
die();
?>
