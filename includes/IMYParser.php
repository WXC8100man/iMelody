<?php

namespace IMY;

use Parser;
use Html;
use OutputPage;
use WikiPage;

class IMYParser {

public static function onParserFirstCallInit( Parser $parser ) {
$parser->setFunctionHook( 'imy', [ self::class, 'renderIMY' ] );
return true;
}

public static function renderIMY( Parser $parser, $input = '' ) {
$parser->getOutput()->addModules( [ 'ext.iMelody.player' ] );
return [ self::buildPlayerHTML( $input ), 'noparse' => true, 'isHTML' => true ];
}

public static function onOutputPageBeforeHTML( OutputPage $out, &$text ) {
$title = $out->getTitle();
if ( !$title || $title->getNamespace() !== 5800 || !$title->exists() ) {
return true;
}

$wikiPage = \MediaWiki\MediaWikiServices::getInstance()
->getWikiPageFactory()
->newFromTitle( $title );

$content = $wikiPage->getContent();
if ( $content ) {
$rawText = $content->getNativeData();
$out->addModules( [ 'ext.iMelody.player' ] );
$player = self::buildPlayerHTML( $rawText );
$text = $player . $text;
}

return true;
}

private static function buildPlayerHTML( $rawIMY ) {
$button = Html::element( 'button', [
'type' => 'button',
'class' => 'imy-play-btn'
], '▶ Play iMelody' );

$pre = Html::element( 'pre', [
'class' => 'imy-source-code'
], trim( $rawIMY ) );

return Html::rawElement( 'div', [
'class' => 'imy-player-box',
'data-imy' => $rawIMY
], $button . $pre );
}
}
EOF


//The inputs should contain a IMY name
//The output should also be wikitext.

add namespace what php
MediaWiki does not recognize "namespaces" directly inside extension.json unless registered via the CanonicalNamespaces hook in PHP, and changing the top-left tab from "page" to "IMY" requires an i18n/en.json message file (nstab-imy). 
