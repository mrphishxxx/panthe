/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	 config.toolbar = 'MyToolbar';
	 /*
	    config.toolbar_MyToolbar =
	    [
	        ['NewPage'],
	        ['Cut','Copy','Paste','PasteText','PasteFromWord','-','Scayt'],
	        ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
	        ['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak'],
	        '/',
	        ['Styles','Format'],
	        ['Bold','Italic','Strike'],
	        ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
	        ['Link','Unlink','Anchor'],
	        ['Maximize','-','About']
	    ];
	    */
	    // Полная версия
	    
	    config.toolbar_MyToolbar =
		    [
		     ['Source','-','Save','NewPage','Preview','-','Templates'],
		     ['Cut','Copy','Paste','PasteText','PasteFromWord','-','Print', 'SpellChecker', 'Scayt'],
		     ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
		     ['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'],
		     ['BidiLtr', 'BidiRtl'],
		     '/',
		     ['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
		     ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote','CreateDiv'],
		     ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
		     ['Link','Unlink','Anchor'],
		     ['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak'],
		     '/',
		     ['Styles','Format','Font','FontSize'],
		     ['TextColor','BGColor'],
		     ['Maximize', 'ShowBlocks','-','About']

		    ];
	    
	    
	    
	    
	    
	    

	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
};
