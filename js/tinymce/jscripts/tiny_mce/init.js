	tinyMCE.init({
		// General options
		mode : "textareas",
		theme : "advanced",
		plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,wordcount,advlist,autosave",

		// Theme options
		theme_advanced_buttons1 : "bold,italic,|,justifyleft,justifycenter,justifyright,justifyfull,|,link,unlink,image,code,|,forecolor,charmap,emotions,|,pasteword,|,bullist,numlist",
		theme_advanced_buttons2 : "",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,

		//Mad File Manager
		
		relative_urls : false,
		file_browser_callback : MadFileBrowser
	});
	
	function MadFileBrowser(field_name, url, type, win) {
	  tinyMCE.activeEditor.windowManager.open({
	      file : "filemanager.php?field=" + field_name + "&url=" + url + "",
	      title : 'File Manager',
	      width : 640,
	      height : 450,
	      resizable : "no",
	      inline : "yes",
	      close_previous : "no"
	  }, {
	      window : win,
	      input : field_name
	  });
	  return false;
	}