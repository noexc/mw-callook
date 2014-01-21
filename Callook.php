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
  $parser->setFunctionHook( 'callsignlink', 'CallsignLinkFunction' );
  return true;
}

function GetCallookJson( $callsign ) {
  $json = @file_get_contents( 'http://callook.info/' . urlencode( $callsign ) . '/json' );
  $json_decoded = json_decode( $json, true );
  return $json_decoded;
} 

function CallsignFunction( $parser, $callsign = '' ) {
  $json_decoded = GetCallookJson( $callsign );
  if ( $json_decoded === NULL ) {
    return array ( 'Unable to look up callsign.', 'isHTML' => true );
  }

  $name = ucwords( strtolower( htmlspecialchars( $json_decoded['name'] ) ) );
  $output = $name . ' (<a href="http://callook.info/' .
    urlencode( $callsign ) . '">' . strtoupper( $callsign ) . '</a>)';

  return array ( $output, 'isHTML' => true );
}

function CallsignLinkFunction( $parser, $callsign = '' ) {
  $output = '<a href="http://callook.info/' . urlencode( $callsign ) . '">' .
    strtoupper( $callsign ) . '</a>';

  return array ( $output, 'isHTML' => true );
}
