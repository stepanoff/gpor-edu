<div id="emails_<?php echo $inputId; ?>"></div>

<style>
.emails__row__input {width: 200px; display: inline;}
</style>

<script type="text/javascript">
jQuery(function($) {
	var emails = <?php echo CJSON::encode($model->$attribute); ?>;

	var emailsEditor_<?php echo $inputId; ?> = function(options) {
		var obj = $("#emails_<?php echo $inputId; ?>");
		if (!obj) {
			return false;
		}

		var currentIndex = 1;

		var createRow = function (data, addBtn) {
			addBtn = addBtn ? addBtn : false;
			var emailInput = $('<input>');
			emailInput.attr("type", "text");
			emailInput.attr("name", options['inputName']+"["+currentIndex+"][email]");
			emailInput.attr("value", data['email']);
			emailInput.attr("class", "form-control emails__row__input emails__row__emailInput");

			var emailTextInput = $('<input>');
			emailTextInput.attr("type", "text");
			emailTextInput.attr("name", options['inputName']+"["+currentIndex+"][text]");
			emailTextInput.attr("value", data['text']);
			emailTextInput.attr("class", "form-control emails__row__input emails__row__textInput");

			var a = $('<a>');
			a.attr("href", "#");
			a.attr("class", "btn btn-default emails__row__delete");
			a.html('<span class="glyphicon glyphicon-remove"></span>');

			var add = $('<a>');
			add.attr("href", "#");
			add.attr("class", "btn btn-default emails__row__add");
			add.html('<span class="glyphicon glyphicon-plus"></span>');

			var row = $('<div>');
			row.attr("class", "form-group emails__row emails__row_"+currentIndex);

			row.append(emailInput);
			row.append(emailTextInput);
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
			createRow({"text": "", "email": ""}, true);

			for (i in options['emails']) {
				createRow(options['emails'][i]);
			}

			obj.delegate('.emails__row__delete', 'click', function(){
				var _this = $(this);
				deleteRow(_this);
				return false;
			});

			obj.delegate('.emails__row__add', 'click', function(){
				var _this = $(this);
				addRow(_this);
				return false;
			});
		};

		var deleteRow = function(el) {
			var row = $(el).closest(".emails__row");
			row.remove();
		}

		var addRow = function(el) {
			var row = $(el).closest(".emails__row");
			var emailInput = row.find(".emails__row__emailInput");
			var textInput = row.find(".emails__row__textInput");
			data = {};
			data['email'] = emailInput.attr("value");
			data['text'] = textInput.attr("value");
			emailInput.attr("value", "");
			textInput.attr("value", "");
			createRow(data);
		}

		init();

	}

	var emailsEditor_<?php echo $inputId; ?>Obj = new emailsEditor_<?php echo $inputId; ?>({
		'emails' : <?php echo CJSON::encode($model->$attribute); ?>,
		'inputName' : '<?php echo $inputName; ?>',
		'inputId' : '<?php echo $inputId; ?>'
	});
});
</script>