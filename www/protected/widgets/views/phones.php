<div id="phones_<?php echo $inputId; ?>"></div>

<style>
.phones__row__input {width: 200px; display: inline;}
</style>

<script type="text/javascript">
jQuery(function($) {
	var phones = <?php echo CJSON::encode($model->$attribute); ?>;

	var phonesEditor_<?php echo $inputId; ?> = function(options) {
		var obj = $("#phones_<?php echo $inputId; ?>");
		if (!obj) {
			return false;
		}

		var currentIndex = 1;

		var createRow = function (data, addBtn) {
			addBtn = addBtn ? addBtn : false;
			var phoneInput = $('<input>');
			phoneInput.attr("type", "text");
			phoneInput.attr("name", options['inputName']+"["+currentIndex+"][phone]");
			phoneInput.attr("value", data['phone']);
			phoneInput.attr("class", "form-control phones__row__input phones__row__phoneInput");

			var phoneTextInput = $('<input>');
			phoneTextInput.attr("type", "text");
			phoneTextInput.attr("name", options['inputName']+"["+currentIndex+"][text]");
			phoneTextInput.attr("value", data['text']);
			phoneTextInput.attr("class", "form-control phones__row__input phones__row__textInput");

			var a = $('<a>');
			a.attr("href", "#");
			a.attr("class", "btn btn-default phones__row__delete");
			a.html('<span class="glyphicon glyphicon-remove"></span>');

			var add = $('<a>');
			add.attr("href", "#");
			add.attr("class", "btn btn-default phones__row__add");
			add.html('<span class="glyphicon glyphicon-plus"></span>');

			var row = $('<div>');
			row.attr("class", "form-group phones__row phones__row_"+currentIndex);

			row.append(phoneInput);
			row.append(phoneTextInput);
			if (addBtn) {
				row.append(add);
			}
			else {
				row.append(a);
			}

			obj.append(row);
		    obj.find(".phones__row_"+currentIndex).find(".phones__row__phoneInput").mask('+7 (999) 999-99-99');
			currentIndex++;
		}

		var init = function() {
		    $.mask.definitions['~']='[+-]';
			createRow({"text": "", "phone": ""}, true);

			for (i in options['phones']) {
				createRow(options['phones'][i]);
			}

			obj.delegate('.phones__row__delete', 'click', function(){
				var _this = $(this);
				deleteRow(_this);
				return false;
			});

			obj.delegate('.phones__row__add', 'click', function(){
				var _this = $(this);
				addRow(_this);
				return false;
			});
		};

		var deleteRow = function(el) {
			var row = $(el).closest(".phones__row");
			row.remove();
		}

		var addRow = function(el) {
			var row = $(el).closest(".phones__row");
			var phoneInput = row.find(".phones__row__phoneInput");
			var textInput = row.find(".phones__row__textInput");
			data = {};
			data['phone'] = phoneInput.attr("value");
			data['text'] = textInput.attr("value");
			phoneInput.attr("value", "");
			textInput.attr("value", "");
			createRow(data);
		}

		init();

	}

	var phonesEditor_<?php echo $inputId; ?>Obj = new phonesEditor_<?php echo $inputId; ?>({
		'phones' : <?php echo CJSON::encode($model->$attribute); ?>,
		'inputName' : '<?php echo $inputName; ?>',
		'inputId' : '<?php echo $inputId; ?>'
	});
});
</script>