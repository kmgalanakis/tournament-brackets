const CopyWebpackPlugin = require('copy-webpack-plugin');
const ExtractTextPlugin = require( 'extract-text-webpack-plugin' );
const StyleLintPlugin = require('stylelint-webpack-plugin');
// const UglifyJSPlugin = require('uglifyjs-webpack-plugin');
const path = require( 'path' );

const tournamentBracketsCSSPlugin = new ExtractTextPlugin({
    filename: './assets/css/tournament-brackets.css'
});
const tournamentBracketsAdminCSSPlugin = new ExtractTextPlugin({
	filename: './assets/css/tournament-brackets-admin.css'
});

// Configuration for the ExtractTextPlugin.
const extractConfig = {
    use: [
        { loader: 'raw-loader' },
        {
            loader: 'postcss-loader',
            options: {
                plugins: [ require( 'autoprefixer' ) ]
            }
        },
        {
            loader: 'sass-loader',
            query: {
                outputStyle:
                    'production' === process.env.NODE_ENV ? 'compressed' : 'nested'
            }
        }
    ]
};

module.exports = function( env ) {
    return {
        entry: {
            './assets/js/tournament-brackets-admin': './res/js/tournament-brackets-admin.js',
        },
        output: {
            path: path.resolve( __dirname ),
            filename: '[name].js'
        },
        watch: true,
        devtool: 'source-map',
        module: {
            rules: [
                // Setup ESLint loader for JS.
                {
                    enforce: 'pre',
                    test: /\.js$/,
                    exclude: /node_modules/,
                    loader: 'eslint-loader',
                    options: {
                        emitWarning: true,
                    }
                },
                {
                    test: /\.js$/,
                    exclude: /(node_modules|bower_components)/,
                    use: {
                        loader: 'babel-loader',
                    },
                },
                {
                    test: /tournament-brackets-admin\.s?css$/,
                    use: tournamentBracketsAdminCSSPlugin.extract( extractConfig )
                },
				{
					test: /tournament-brackets\.s?css$/,
					use: tournamentBracketsCSSPlugin.extract( extractConfig )
				}
            ]
        },
        plugins: [
            tournamentBracketsCSSPlugin,
			tournamentBracketsAdminCSSPlugin,
            new StyleLintPlugin({
                syntax: 'scss'
            }),
            // new UglifyJSPlugin({
            //     uglifyOptions: {
            //         mangle: {
            //             // Dont mangle these
            //             reserved: ['$super', '$', 'exports', 'require']
            //         }
            //     },
            //     sourceMap: true
            // }),
            new CopyWebpackPlugin([
                // JS
                { from: './node_modules/jquery-bracket/dist/jquery.bracket.min.js', to: './assets/js/third-party' },
				{ from: './node_modules/jquery-tennis-bracket/dist/jquery.bracket.min.js', to: './assets/js/third-party/jquery.tennis.bracket.min.js' },
                // CSS
                { from: './node_modules/jquery-bracket/dist/jquery.bracket.min.css', to: './assets/css/third-party' },
				{ from: './node_modules/jquery-bracket/dist/jquery.bracket.min.css', to: './assets/css/third-party/jquery.tennis.bracket.min.css' },
                //
            ])
        ]
    }
};