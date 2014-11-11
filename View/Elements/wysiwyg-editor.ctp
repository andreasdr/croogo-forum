<?php

echo $this->Html->css('Utility.decoda.min', 'stylesheet', array('inline' => false));
echo $this->Html->css('Forum.sceditor/default.css', 'stylesheet', array('inline' => false));
echo $this->Html->css('Forum.sceditor/custom.css', 'stylesheet', array('inline' => false));
echo $this->Html->script('Forum.sceditor/jquery.sceditor.bbcode.js', array('inline' => false));

?>

<script type="text/javascript">
if (typeof windowOnLoadFunctions == "undefined") {
	windowOnLoadFunctions = new Array();
	$(window).load(function() {
		while (windowOnLoadFunctions.length > 0) {
			var onLoadFunction = windowOnLoadFunctions.pop();
			onLoadFunction();
		}
	});
}
windowOnLoadFunctions.push(function() {
	$.sceditor.defaultOptions.emoticonsRoot = '<?php echo explode('?', $this->Html->assetUrl('Forum.img/'))[0] ?>'; 
	// spoiler support, could be moved into a external js-file
	$.sceditor.plugins.bbcode.bbcode.set("spoiler", {
		allowsEmpty: true,
		tags: {
			pre: null
		},
		isInline: false,
		format: function (element, content) {
			return '[spoiler]' + content + '[/spoiler]';
	    },
		html: function (element, attrs, content) {
			return '<pre class="spoiler">' + content + '</pre>\n';
		}
	});
	$.sceditor.command.set("spoiler", {
		forceNewLineAfter: ['pre'],
		exec: function (caller) {
			this.wysiwygEditorInsertHtml('<pre class="spoiler">', '</pre>');
	    },
	    txtExec: function () {
			this.insertText("[spoiler]", "[/spoiler]");
		},
		tooltip: 'Insert a spoiler'
	});

	// start up
	$("#<?php echo $id; ?>").sceditor({
		plugins: "bbcode",
		style: "/forum/css/sceditor/jquery.sceditor.default.min.css",
		toolbar: "bold,italic,underline,strike,subscript,superscript|left,center,right,justify|font,size,color,removeformat|bulletlist,orderedlist|code,quote|youtube,email,link,image,unlink|emoticon|spoiler|source",
		width: '100%',
		height: '384'
	});
});
</script>
