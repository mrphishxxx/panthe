/*---------------------------------------------------------
  Configuration
---------------------------------------------------------*/

// Set culture to display localized messages
var culture = 'ru_utf8';

// Autoload text in GUI
var autoload = true;

// Display full path - default : false
var showFullPath = false;

// Set this to the server side language you wish to use.
var lang = 'php'; // options: php, jsp // we are looking for contributors for lasso, python connectors (partially developed)

var am = document.location.pathname.substring(1, document.location.pathname
		.lastIndexOf('/') + 1);
// Set this to the directory you wish to manage.

//var fileRoot = '/' + am + 'userfiles/';
var fileRoot = '/';

// Show image previews in grid views?
var showThumbs = true;
