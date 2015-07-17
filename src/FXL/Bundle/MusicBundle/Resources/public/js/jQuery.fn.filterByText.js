jQuery.fn.filterByText = function(textbox) {
    return this.each(function() {
        var select = this;
        var options = [];
        $(select).find('option').each(function() {
            options.push({value: $(this).val(), text: $(this).text()});
        });
        $(select).data('options', options);

        var selectOptions = $("option:selected", select);

        $(textbox).bind('change keyup', function() {

            var options = $(select).empty().data('options');

            var selectedOptions = [];

            $(selectOptions).each(function(key, selectOption){

                selectedOptions.push({value: $(selectOption).val(), text: $(selectOption).text()});

                //selectOptionId[] = $(selectOption).attr("value");


            });


            var search = $.trim($(this).val());
            var regex = new RegExp(search,"gi");

            $(selectedOptions).each(function(i){

                var option = selectedOptions[i];
                var myOption = $('<option>');

                $(select).append(
                    myOption.text(option.text).val(option.value)
                );

                myOption.attr("selected", "selected");
            });

            $.each(options, function(i) {
                var option = options[i];
                if(option.text.match(regex) !== null) {

                    var myOption = $('<option>');

                    if(! selectedOptions.inArray(option.text)) {

                        $(select).append(
                            myOption.text(option.text).val(option.value)
                        );
                    }
                }
                /**/
            });

            //console.log($('option', select));

            $('option', select).click(function(){

                $("#ajax_form").mask();

                $.ajax({
                    type: 'GET',
                    url: "/admin/ajax/author/" + $(this).attr("value") + "/edit?project=" + $("#project-selection option:selected").attr("value"),
                    //data: data,
                    success: function(html){

                        $("#ajax_form").html(html);

                        $("#ajax_form").unmask();

                    }
                });
            });

        });
    });
};