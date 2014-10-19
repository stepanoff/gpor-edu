<div id="customText_<?php echo $inputId; ?>"></div>

<style>
.customText__row__cutomTextInput {
	width: 50%;
}
</style>

<script type="text/javascript">
jQuery(function($) {
	var customText = <?php echo CJSON::encode($model->$attribute); ?>;

	var customTextEditor_<?php echo $inputId; ?> = function(options) {
		var obj = $("#customText_<?php echo $inputId; ?>");
		if (!obj) {
			return false;
		}

		var currentIndex = 1;

		var createRow = function (data, addBtn) {
			addBtn = addBtn ? addBtn : false;
			var customTextInput = $('<input>');
			customTextInput.attr("type", "text");
			customTextInput.attr("name", options['inputName']+"["+currentIndex+"][title]");
			customTextInput.attr("value", data['title']);
			customTextInput.attr("class", "form-control customText__row__input customText__row__customTextInput");

			var customTextTextInput = $('<textarea>');
			customTextTextInput.attr("type", "text");
			customTextTextInput.attr("name", options['inputName']+"["+currentIndex+"][text]");
			customTextTextInput.val(data['text']);
			customTextTextInput.attr("class", "form-control customText__row__input customText__row__textInput");

			var a = $('<a>');
			a.attr("href", "#");
			a.attr("class", "btn btn-default customText__row__delete");
			a.html('<span class="glyphicon glyphicon-remove"></span>');

			var add = $('<a>');
			add.attr("href", "#");
			add.attr("class", "btn btn-default customText__row__add");
			add.html('<span class="glyphicon glyphicon-plus"></span>');

			var row = $('<div>');
			row.attr("class", "form-group customText__row customText__row_"+currentIndex);

			row.append(customTextInput);
			row.append(customTextTextInput);
			if (addBtn) {
				row.append(add);
			}
			else {
				row.append(a);
			}

			obj.append(row);
			currentIndex++;
		}

		var init = function() {
			createRow({"title": "", "text": ""}, true);

			for (i in options['customText']) {
				createRow(options['customText'][i]);
			}

			obj.delegate('.customText__row__delete', 'click', function(){
				var _this = $(this);
				deleteRow(_this);
				return false;
			});

			obj.delegate('.customText__row__add', 'click', function(){
				var _this = $(this);
				addRow(_this);
				return false;
			});
		};

		var deleteRow = function(el) {
			var row = $(el).closest(".customText__row");
			row.remove();
		}

		var addRow = function(el) {
			var row = $(el).closest(".customText__row");
			var customTextInput = row.find(".customText__row__customTextInput");
			var textInput = row.find(".customText__row__textInput");
			data = {};
			data['title'] = customTextInput.attr("value");
			data['text'] = textInput.val();
			customTextInput.attr("value", "");
			textInput.val("");
			createRow(data);
		}

		init();

	}

	var customTextEditor_<?php echo $inputId; ?>Obj = new customTextEditor_<?php echo $inputId; ?>({
		'customText' : <?php echo CJSON::encode($model->$attribute); ?>,
		'inputName' : '<?php echo $inputName; ?>',
		'inputId' : '<?php echo $inputId; ?>'
	});
});
</script>