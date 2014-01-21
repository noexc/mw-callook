<?php

$wgExtensionCredits['parserhook'][] = array(
    'path' => __FILE__,
    'name' => 'Callook',
    'author' => 'Ricky Elrod',
    'url' => 'https://github.com/CodeBlock/mw-callook',
    'description' => "This extension provides an easy way to reference ham radio operators.",
    'version'  => 1.0,
);

$wgHooks['ParserFirstCallInit'][] = 'CallookSetupParserFunction';
$wgExtensionMessagesFiles['Callook'] = dirname( __FILE__ ) . '/Callook.i18n.php';

function CallookSetupParserFunction( &$parser ) {
  $parser->setFunctionHook( 'callsign', 'CallsignFunction' );
  $parser->setFunctionHook( 'callsignlist', 'CallsignListFunction' );
  $parser->setFunctionHook( 'callsignlink', 'CallsignLinkFunction' );
  return true;
}

/** Helper function to get callook JSON response. */
function GetCallookJson( $callsign ) {
  $json = @file_get_contents( 'http://callook.info/' . urlencode( $callsign ) . '/json' );
  $json_decoded = json_decode( $json, true );
  return $json_decoded;
} 

/** Format a callsign into the following format:
    Name Here (<link to callook profile here>)
*/
function FormatSimple( $callsign ) {
  $json_decoded = GetCallookJson( $callsign );
  if ( $json_decoded === NULL ) {
    return 'Unable to look up callsign "' . $callsign . '".';
  } else {
    $name = ucwords( strtolower( htmlspecialchars( $json_decoded['name'] ) ) );
    return $name . ' ([http://callook.info/' . urlencode( $callsign ) . ' ' .
      strtoupper( $callsign ) . "])";
  }
}

/** Format a callsign by displaying the person's name and callsign (which is
    linked to their callook.info profile.).
*/
function CallsignFunction( $parser, $callsign = '' ) {
  return array ( FormatSimple( $callsign ) );
}

/** Link a callsign to its callook.info profile. **/
function CallsignLinkFunction( $parser, $callsign = '' ) {
  $output = '[http://callook.info/' . urlencode( $callsign ) . ' ' .
    strtoupper( $callsign ) . ']';
  return array ( $output );
}

/** Take a list of callsigns (comma-separated), and display their owner's name
    and callsign (linked to callook.info) as a list element.
*/
function CallsignListFunction( $parser, $callsign = '' ) {
  $callsigns = explode( ',', str_replace ( ' ', '', $callsign ) );
  $output = '';
  $formatted = array_map( 'FormatSimple', $callsigns );
  sort( $formatted );
  foreach ( $formatted as $i ) {
    $output .= '* ' . $i . "\n";
  }
  return array ( $output );
}
