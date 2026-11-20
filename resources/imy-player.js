( function () {
	'use strict';

	var NOTE_FREQS = {
		'c': 261.63, '#c': 277.18, '&d': 277.18,
		'd': 293.66, '#d': 311.13, '&e': 311.13,
		'e': 329.63,
		'f': 349.23, '#f': 369.99, '&g': 369.99,
		'g': 392.00, '#g': 415.30, '&a': 415.30,
		'a': 440.00, '#a': 466.16, '&b': 466.16,
		'b': 493.88
	};

	function parseIMY( rawText ) {
		var beatMatch = rawText.match( /BEAT:(\d+)/i );
		var bpm = beatMatch ? parseInt( beatMatch[ 1 ], 10 ) : 120;
		var melodyMatch = rawText.match( /MELODY:([^\r\n]+)/i );
		var melody = melodyMatch ? melodyMatch[ 1 ] : rawText;

		var tokens = melody.match( /(\*\d+)?([#&]?[a-gr])(\d+)([.;])?/gi ) || [];
		var octave = 4;
		var quarterNoteSec = 60 / bpm;

		return tokens.map( function ( token ) {
			var parts = token.match( /^(?:\*(\d+))?([#&]?[a-gr])(\d+)([.;])?$/i );
			if ( parts[ 1 ] ) {
				octave = parseInt( parts[ 1 ], 10 );
			}
			var noteName = parts[ 2 ].toLowerCase();
			var durCode = parseInt( parts[ 3 ], 10 );
			var modifier = parts[ 4 ] || '';

			var duration = ( 4 / Math.pow( 2, durCode ) ) * quarterNoteSec;
			if ( modifier === '.' ) {
				duration *= 1.5;
			} else if ( modifier === ';' ) {
				duration *= ( 2 / 3 );
			}

			var freq = 0;
			if ( noteName !== 'r' && NOTE_FREQS[ noteName ] ) {
				freq = NOTE_FREQS[ noteName ] * Math.pow( 2, octave - 4 );
			}

			return { freq: freq, duration: duration };
		} );
	}

	function playNotes( notes ) {
		var AudioCtx = window.AudioContext || window.webkitAudioContext;
		var ctx = new AudioCtx();
		var time = ctx.currentTime;

		notes.forEach( function ( note ) {
			if ( note.freq > 0 ) {
				var osc = ctx.createOscillator();
				var gain = ctx.createGain();
				osc.type = 'square';
				osc.frequency.setValueAtTime( note.freq, time );
				gain.gain.setValueAtTime( 0.1, time );
				gain.gain.exponentialRampToValueAtTime( 0.001, time + note.duration * 0.9 );

				osc.connect( gain );
				gain.connect( ctx.destination );
				osc.start( time );
				osc.stop( time + note.duration );
			}
			time += note.duration;
		} );
	}

	mw.hook( 'wikipage.content' ).add( function ( $content ) {
		$content.find( '.imy-play-btn' ).off( 'click.imy' ).on( 'click.imy', function () {
			var rawData = $( this ).closest( '.imy-player-box' ).attr( 'data-imy' ) || '';
			var notes = parseIMY( rawData );
			if ( notes.length ) {
				playNotes( notes );
			}
		} );
	} );
}() );
