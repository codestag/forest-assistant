/**
 * Gulp Configuration File
 *
 * 1. Edit the variables as per your project requirements.
 *
 * @package Gulp
 */

module.exports = {

    // Project options.
    projectURL: 'stagthemes.local/forest', // Local project URL of your already running WordPress site. Could be something like wp.local or localhost:3000 depending upon your local WordPress setup.
    packageJSON: './package.json',

    watchPhp: [ '**/*.php', '!build/**', '!languages/**', '!node_modules/**' ], // Path to all PHP files.
    // Translation options.
    textDomain: 'forest-assistant', // Your textdomain here.
    translationFile: 'forest-assistant.pot', // Name of the translation file.
    translationDestination: './languages', // Where to save the translation files.
    bugReport: 'https://github.com/codestag/forest-assistant/issues', // Where can users report bugs.
    lastTranslator: 'Krishna Kant <krishna@codestag.com>', // Last translator Email ID.
    team: 'Codestag <hello@codestag.com>', // Team's Email ID.

    export: {
        src: [
            '**/*',
            '!gulp.config.js',
            '!.dev/**/*',
            '!node_modules',
            '!node_modules/**/*',
            '!vendor',
            '!vendor/**/*',
            '!build',
            '!build/**/*',
            '!.*',
            '!**/*.css.map',
            '!composer.*',
            '!googlefonts.json',
            '!google-fonts-array.php',
            '!config.*',
            '!gulpfile.*',
            '!package*.*',
            '!phpcs.*',
            '!*.lock',
            '!*.zip'
        ],
        dest: './build/',
    }
};