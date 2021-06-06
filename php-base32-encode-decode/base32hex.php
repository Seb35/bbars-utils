<?php

$N = 3000;
$n = 3;

require_once __DIR__ . '/Base32.php';

$collator = new Collator( 'uca-default-u-kn' );
$numbers = [];
for( $i = 0; $i < $N; $i++ ) {
	$numbers[] = $collator->getSortKey( 'Tewjfiewfuiewhfuhcuiewcfjeifiewvfiueruivffiuierqivrvniuernvernewcfuirhsdui' . ((string) 11*$i) );
}

function sumLengthStrings( $currentSum, $item ) {
	return $currentSum + strlen( $item );
}

const base64hexFrom = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/';
const base64hexTo   = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz{}';
function base64hex_encode( $string ) {
	return rtrim( strtr( base64_encode( $string ), base64hexFrom, base64hexTo ), '=' );
}
function base64hex_decode( $string ) {
	return base64_decode( strtr( $string, base64hexTo, base64hexFrom ) );
}

function uuencode( $string ) {
	return preg_replace( "/\n./", '', convert_uuencode( $string ) );
}
// non-working for string longer than 45 bits, it should be added the 'length' at the beginning of the line, see uuencode algorithm
function uudecode( $string ) {
	return convert_uudecode( implode( "\n", str_split( $string, 45 ) ) );
}








echo "base64hex:\n";
$timeStart = microtime( true );

for( $i = 0; $i < $N; $i++ ) {
	$numbers64hex[] = Base64hex::encode( $numbers[$i] );
}

$timeEnd = microtime( true );
$time64hex = $timeEnd - $timeStart;

#var_dump( $numbers64hex );
var_dump( array_slice( $numbers64hex, 0, $n ) );
if( array_map( 'Base64hex::decode', $numbers64hex ) != $numbers ) {
	echo "!!! NON-REVERSIBLE\n";
}
echo 'Time for base64hex = ';
var_dump( ( $timeEnd - $timeStart ) / $N );
echo 'Mean length = ';
var_dump( array_reduce( $numbers64hex, 'sumLengthStrings', 0 )/$N );
echo "\n";


echo "base32hex:\n";
$timeStart = microtime( true );

for( $i = 0; $i < $N; $i++ ) {
	$numbers32hex[] = Base32hex::encode( $numbers[$i] );
}

$timeEnd = microtime( true );
$time32hex = $timeEnd - $timeStart;

#var_dump( $numbers32hex );
var_dump( array_slice( $numbers32hex, 0, $n ) );
if( array_map( 'Base32hex::decode', $numbers32hex ) !== $numbers ) {
	echo "!!! NON-REVERSIBLE\n";
}
echo 'Time for base32hex = ';
var_dump( ( $timeEnd - $timeStart ) / $N );
echo 'Mean length = ';
var_dump( array_reduce( $numbers32hex, 'sumLengthStrings', 0 )/$N );
echo "\n";


echo "base16hex:\n";
$timeStart = microtime( true );

for( $i = 0; $i < $N; $i++ ) {
	$numbers16hex[] = Base16hex::encode( $numbers[$i] );
}

$timeEnd = microtime( true );
$time16hex = $timeEnd - $timeStart;

#var_dump( $numbers16hex );
var_dump( array_slice( $numbers16hex, 0, $n ) );
if( array_map( 'Base16hex::decode', $numbers16hex ) !== $numbers ) {
	echo "!!! NON-REVERSIBLE\n";
}
echo 'Time for base16hex = ';
var_dump( ( $timeEnd - $timeStart ) / $N );
echo 'Mean length = ';
var_dump( array_reduce( $numbers16hex, 'sumLengthStrings', 0 )/$N );
echo "\n";


echo "64uuencode:\n";
$timeStart = microtime( true );

for( $i = 0; $i < $N; $i++ ) {
	$numbers64uuencode[] = convert_uuencode( $numbers[$i] );
}

$timeEnd = microtime( true );
$time64uuencode = $timeEnd - $timeStart;

#var_dump( $numbers64uuencode );
var_dump( array_slice( $numbers64uuencode, 0, $n ) );
if( array_map( 'convert_uudecode', $numbers64uuencode ) !== $numbers ) {
	echo "!!! NON-REVERSIBLE\n";
}
echo 'Time for base64uuencode = ';
var_dump( ( $timeEnd - $timeStart ) / $N );
echo 'Mean length = ';
var_dump( array_reduce( $numbers64uuencode, 'sumLengthStrings', 0 )/$N );
echo "\n";


echo "64base:\n";
$timeStart = microtime( true );

for( $i = 0; $i < $N; $i++ ) {
	$numbers64base[] = base64_encode( $numbers[$i] );
}

$timeEnd = microtime( true );
$time64base = $timeEnd - $timeStart;

#var_dump( $numbers64base );
var_dump( array_slice( $numbers64base, 0, $n ) );
if( array_map( 'base64_decode', $numbers64base ) !== $numbers ) {
	echo "!!! NON-REVERSIBLE\n";
}
echo 'Time for base64base = ';
var_dump( ( $timeEnd - $timeStart ) / $N );
echo 'Mean length = ';
var_dump( array_reduce( $numbers64base, 'sumLengthStrings', 0 )/$N );
echo "\n";


echo "64basehex:\n";
$timeStart = microtime( true );

for( $i = 0; $i < $N; $i++ ) {
	$numbers64basehex[] = base64hex_encode( $numbers[$i] );
}

$timeEnd = microtime( true );
$time64basehex = $timeEnd - $timeStart;

#var_dump( $numbers64basehex );
var_dump( array_slice( $numbers64basehex, 0, $n ) );
if( array_map( 'base64hex_decode', $numbers64basehex ) !== $numbers ) {
	echo "!!! NON-REVERSIBLE\n";
	var_dump( array_map( 'bin2hex', array_map( 'base64hex_decode', $numbers64basehex ) ) );
}
if( $numbers64basehex !== $numbers64hex ) {
	echo "!!! base64hex devrait être identique à 64hex\n";
}
echo 'Time for base64basehex = ';
var_dump( ( $timeEnd - $timeStart ) / $N );
echo 'Mean length = ';
var_dump( array_reduce( $numbers64basehex, 'sumLengthStrings', 0 )/$N );
echo "\n";


echo "unpack( 'H*' ):\n";
$timeStart = microtime( true );

for( $i = 0; $i < $N; $i++ ) {
	$numbers16unpack[] = unpack( 'H*', $numbers[$i] )[1];
}

$timeEnd = microtime( true );
$time16unpack = $timeEnd - $timeStart;

#var_dump( $numbers16unpack );
var_dump( array_slice( $numbers16unpack, 0, $n ) );
if( array_map( 'hex2bin', $numbers16unpack ) !== $numbers ) {
	echo "!!! NON-REVERSIBLE\n";
}
echo 'Time for unpack( \'H*\' ) = ';
var_dump( ( $timeEnd - $timeStart ) / $N );
echo 'Mean length = ';
var_dump( array_reduce( $numbers16unpack, 'sumLengthStrings', 0 )/$N );
echo "\n";


echo "bin2hex:\n";
$timeStart = microtime( true );

for( $i = 0; $i < $N; $i++ ) {
	$numbers16bin2hex[] = bin2hex( $numbers[$i] );
}

$timeEnd = microtime( true );
$time16bin2hex = $timeEnd - $timeStart;

#var_dump( $numbers16bin2hex );
var_dump( array_slice( $numbers16bin2hex, 0, $n ) );
if( array_map( 'hex2bin', $numbers16bin2hex ) !== $numbers ) {
	echo "!!! NON-REVERSIBLE\n";
}
echo 'Time for bin2hex = ';
var_dump( ( $timeEnd - $timeStart ) / $N );
echo 'Mean length = ';
var_dump( array_reduce( $numbers16bin2hex, 'sumLengthStrings', 0 )/$N );
echo "\n";


// Current SMW\Collator::armor algorithm - for perf comparison
echo "existing:\n";
$timeStart = microtime( true );

for( $i = 0; $i < $N; $i++ ) {
	$text = str_replace( '�', '', htmlspecialchars( $numbers[$i], ENT_SUBSTITUTE, 'UTF-8' ) );
	$text = preg_replace( '~\s+~u', '?', $text );
	$numbersexisting[] = preg_replace( '~\p{C}+~u', '?', $text );
}

$timeEnd = microtime( true );
$timeexisting = $timeEnd - $timeStart;

#var_dump( $numbers16bin2hex );
var_dump( array_slice( $numbersexisting, 0, $n ) );
echo 'Time for existing = ';
var_dump( ( $timeEnd - $timeStart ) / $N );
echo 'Mean length = ';
var_dump( array_reduce( $numbersexisting, 'sumLengthStrings', 0 )/$N );
echo "\n";

echo "Time:\n";
echo '16bin2hex → 16unpack = ';
var_dump( $time16unpack / $time16bin2hex );
echo '16bin2hex → 16hex = ';
var_dump( $time16hex / $time16bin2hex );
echo '16bin2hex → 32hex = ';
var_dump( $time32hex / $time16bin2hex );
echo '16bin2hex → 64hex = ';
var_dump( $time64hex / $time16bin2hex );
echo '16bin2hex → 64uuencode = ';
var_dump( $time64uuencode / $time16bin2hex );
echo '16bin2hex → 64base = ';
var_dump( $time64base / $time16bin2hex );
echo '16bin2hex → 64basehex = ';
var_dump( $time64basehex / $time16bin2hex );
echo '16bin2hex → existing = ';
var_dump( $timeexisting / $time16bin2hex );
echo "\n";
echo "Space:\n";
echo '16bin2hex → 16unpack = ';
var_dump( array_reduce( $numbers16unpack, 'sumLengthStrings', 0 ) / array_reduce( $numbers16bin2hex, 'sumLengthStrings', 0 ) );
echo '16bin2hex → 16hex = ';
var_dump( array_reduce( $numbers16hex, 'sumLengthStrings', 0 ) / array_reduce( $numbers16bin2hex, 'sumLengthStrings', 0 ) );
echo '16bin2hex → 32hex = ';
var_dump( array_reduce( $numbers32hex, 'sumLengthStrings', 0 ) / array_reduce( $numbers16bin2hex, 'sumLengthStrings', 0 ) );
echo '16bin2hex → 64hex = ';
var_dump( array_reduce( $numbers64hex, 'sumLengthStrings', 0 ) / array_reduce( $numbers16bin2hex, 'sumLengthStrings', 0 ) );
echo '16bin2hex → 64uuencode = ';
var_dump( array_reduce( $numbers64uuencode, 'sumLengthStrings', 0 ) / array_reduce( $numbers16bin2hex, 'sumLengthStrings', 0 ) );
echo '16bin2hex → 64base = ';
var_dump( array_reduce( $numbers64base, 'sumLengthStrings', 0 ) / array_reduce( $numbers16bin2hex, 'sumLengthStrings', 0 ) );
echo '16bin2hex → 64basehex = ';
var_dump( array_reduce( $numbers64basehex, 'sumLengthStrings', 0 ) / array_reduce( $numbers16bin2hex, 'sumLengthStrings', 0 ) );
echo '16bin2hex → existing = ';
var_dump( array_reduce( $numbersexisting, 'sumLengthStrings', 0 ) / array_reduce( $numbers16bin2hex, 'sumLengthStrings', 0 ) );

/**

Sample result:

Time (lesser is better, better thant 16bin2hex):
16bin2hex → 16unpack = float(3.4562294592172)
16bin2hex → 16hex = float(82.449955183747)
16bin2hex → 32hex = float(72.01493875112)
16bin2hex → 64hex = float(63.357036151778)
16bin2hex → 64uuencode = float(1.7382730803705)
16bin2hex → 64base = float(0.86077083955781)
16bin2hex → 64basehex = float(3.3860173289513)
16bin2hex → existing = float(13.553032566477)

Space (lesser is better, better thant base16):
16bin2hex → 16unpack = int(1)
16bin2hex → 16hex = int(1)
16bin2hex → 32hex = float(0.80109382749093)
16bin2hex → 64hex = float(0.66938581920948)
16bin2hex → 64uuencode = float(0.71296770079744)
16bin2hex → 64base = float(0.67754327683794)
16bin2hex → 64basehex = float(0.66938581920948)
16bin2hex → existing = float(0.52592083822059)

Remarks and conclusions:
* unpack( 'H*', $text ) gives the same result than bin2hex( $text ) but is 2 times slower
* obviously, the bigger is the base, more compact are the numbers
* uuencode is roughly base 64 with a few more characters (+2 characters every 45 bits)
* base64 is the classical base64 (figures := [A-Za-z0-9+/]) with padding with '=' at the end to get a number of characters multiple of 3
* base64hex is similar to classical base64 but without padding at the end and with figures [0-9A-Za-z{}]: it preserves bitwise sort order: bitwise comparison of original strings is identical to bitwise comparison of base64hex-encoded strings
* 'existing' is a lossy encoding used in Semantic MediaWiki before 4.0.0, computed for perf and space comparison since the goal of this work is to replace it
* The three Base16hex, Base32hex, Base64hex are slower than native PHP functions (implemented in C), see https://stackoverflow.com/a/16679277/1078843, but it is nevertheless a good optimised implementation
* 64basehex is equivalent to Base64hex but it uses the native PHP function base64_encode to gain performance and normalises afterwards the result; they are the same encodings (but 20 times quicker).

 */
