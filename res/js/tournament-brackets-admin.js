import './../css/tournament-brackets.scss';
import './../css/tournament-brackets-admin.scss';

var tournamentBracketsAdmin = function( $ ) {

    var self = this;

    self.renderBracket = function() {

        var tournamentBracketsData = $( '#tournament_brackets_data' ).val();
        if ( '' === tournamentBracketsData ) {
            tournamentBracketsData = '""';
        }
        tournamentBracketsData = JSON.parse( tournamentBracketsData );

        if ( 0 >= tournamentBracketsData.length ) {
            tournamentBracketsData = {
                'teams': [
                    [ null, null ],
                    [ null, null ]
                ],
                'results': [
                    [
                        [
                            [ [ null, null, null ], [ null, null, null ] ],
                            [ [ null, null, null ], [ null, null, null ] ]
                        ],
                        [
                            [ [ null, null, null ], [ null, null, null ] ],
                            [ [ null, null, null ], [ null, null, null ] ]
                        ]
                    ]
                ]
            };
        }

        $( '#tournament-brackets' ).bracket({
            init: tournamentBracketsData,
			centerConnectors: true,
			teamWidth: 180,
			skipConsolationRound: true,
            save: self.saveFn
        });

    };


    /** Called whenever bracket is modified
     *
     * data:     changed bracket object in format given to init
     * userData: optional data given when bracket is created.
     */
    self.saveFn = function( data, userData ) {
        var json = JSON.stringify( data );
        $( '#tournament_brackets_data' ).val( json );
    };

    self.init = function() {
        self.renderBracket();
    };

    self.init();

};

jQuery( document ).ready( function( $ ) {
    var tournamentBracketsAdminInstance = new tournamentBracketsAdmin( $ );
    $( '#tournament-brackets' ).doubleScroll();
});
