<?php
namespace IMY;

use Parser;
use PPFrame;

class IMYParser {
    public static function onParserFirstCallInit( Parser $parser ) {
        $parser->setFunctionHook( 'imy', [ self::class, 'renderIMY' ] );
        return true;
    }

    public static function renderIMY( Parser $parser, $param = '' ) {
        // Example mock data mapping for 'Test'
        $beat = "25";
        $melody = "vibeonr2vibeonr2vibeon (r2vibeonvibeonr2vibeonr2vibeonr2vibeonr2vibeonr2vibeonr2vibeonr2vibeonr2vibeonr2vibeonr2vibeonr2vibeonr2vibeonr2vibeonr2vibeonr2vibeonr2ledonr2vibeonr2vibeon";

        $imyOutput = "BEGIN:IMELODY\n" .
                     "VERSION:1.2\n" .
                     "FORMAT:CLASS1.0\n" .
                     "BEAT:{$beat}\n" .
                     "MELODY:{$melody}\n" .
                     "END:IMELODY";

        // Output formatted code block with synth player placeholder
        $html = "<pre class=\"imy-code\">" . htmlspecialchars( $imyOutput ) . "</pre>" .
                "<div class=\"imy-player\" data-imy=\"" . htmlspecialchars( $imyOutput ) . "\">🎵 Play iMelody Synth</div>";

        return [ $html, 'noparse' => false, 'isHTML' => true ];
    }
}
