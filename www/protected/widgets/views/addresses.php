<div id="addresss_<?php echo $inputId; ?>"></div>

<style>
.addresss__row__input {width: 200px; display: inline;}
</style>

<script type="text/javascript">
jQuery(function($) {
	var addresss = <?php echo CJSON::encode($model->$attribute); ?>;

	var addresssEditor_<?php echo $inputId; ?> = function(options) {
		var obj = $("#addresss_<?php echo $inputId; ?>");
		if (!obj) {
			return false;
		}

		var currentIndex = 1;

		var createRow = function (data, addBtn) {
			addBtn = addBtn ? addBtn : false;
			var addressInput = $('<input>');
			addressInput.attr("type", "text");
			addressInput.attr("name", options['inputName']+"["+currentIndex+"][address]");
			addressInput.attr("value", data['address']);
			addressInput.attr("class", "form-control addresss__row__input addresss__row__addressInput");

			var addressTextInput = $('<input>');
			addressTextInput.attr("type", "text");
			addressTextInput.attr("name", options['inputName']+"["+currentIndex+"][text]");
			addressTextInput.attr("value", data['text']);
			addressTextInput.attr("class", "form-control addresss__row__input addresss__row__textInput");

			var a = $('<a>');
			a.attr("href", "#");
			a.attr("class", "btn btn-default addresss__row__delete");
			a.html('<span class="glyphicon glyphicon-remove"></span>');

			var add = $('<a>');
			add.attr("href", "#");
			add.attr("class", "btn btn-default addresss__row__add");
			add.html('<span class="glyphicon glyphicon-plus"></span>');

			var row = $('<div>');
			row.attr("class", "form-group addresss__row addresss__row_"+currentIndex);

			row.append(addressInput);
			row.append(addressTextInput);
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
			createRow({"text": "", "address": ""}, true);

			for (i in options['addresss']) {
				createRow(options['addresss'][i]);
			}

			obj.delegate('.addresss__row__delete', 'click', function(){
				var _this = $(this);
				deleteRow(_this);
				return false;
			});

			obj.delegate('.addresss__row__add', 'click', function(){
				var _this = $(this);
				addRow(_this);
				return false;
			});
		};

		var deleteRow = function(el) {
			var row = $(el).closest(".addresss__row");
			row.remove();
		}

		var addRow = function(el) {
			var row = $(el).closest(".addresss__row");
			var addressInput = row.find(".addresss__row__addressInput");
			var textInput = row.find(".addresss__row__textInput");
			data = {};
			data['address'] = addressInput.attr("value");
			data['text'] = textInput.attr("value");
			addressInput.attr("value", "");
			textInput.attr("value", "");
			createRow(data);
		}

		init();

	}

	var addresssEditor_<?php echo $inputId; ?>Obj = new addresssEditor_<?php echo $inputId; ?>({
		'addresss' : <?php echo CJSON::encode($model->$attribute); ?>,
		'inputName' : '<?php echo $inputName; ?>',
		'inputId' : '<?php echo $inputId; ?>'
	});
});
</script>