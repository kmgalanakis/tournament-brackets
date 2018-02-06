var tennisBracketsAdmin = function( $ ) {

    var self = this;

    self.renderBracket = function() {

        var tennisBracketDaata = $('#tennis_brackets_data').val();
        if ( '' === tennisBracketDaata ) {
            tennisBracketDaata = '""';
        }
        tennisBracketDaata = JSON.parse( tennisBracketDaata );

        if ( tennisBracketDaata.length <= 0 ) {
            tennisBracketDaata = {
                "teams": [
                    [ null, null ],
                    [ null, null ]
                ],
                "results": [
                    [
                        [
                            [ null, null ],
                            [ null, null ]
                        ],
                        [
                            [ null, null ],
                            [ null, null ]
                        ]
                    ]
                ]
            }
        }

        $( '#tennis-bracket' ).bracket({
            init: tennisBracketDaata,
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
        $('#tennis_brackets_data').val( json );
    };

    self.init = function() {
        self.renderBracket();
    };

    self.init();

};

jQuery( document ).ready( function( $ ) {
    var tennisBracketsAdminInstance = new tennisBracketsAdmin( $ );
});