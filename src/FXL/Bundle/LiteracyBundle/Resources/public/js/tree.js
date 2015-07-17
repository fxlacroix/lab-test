
function refresh_tree(){

    $("#browser").mask("Waiting...");

    $.ajax({
        url: "/admin/folder/list",
        type: "GET",
        dataType: 'html',
        success: function(html) {
            $("#browser").unmask();
            $("#browser").html(html);

            make_tree();
        }
    });
}

function make_tree(){

    $("#browser").treeview({
		cookieId: "treeview-admin",
		collapsed: true,
        persist: "cookie"
		//unique: true
    });

    $("#browser").attr("itemprop", true);

    $(".refresh_tree").click(function(){

        refresh_tree()
    });

    $(".generate").click(function(){

                $("textarea.content").mask();
         $.ajax({
            type: 'GET',
            url: $(this).attr("itemprop"),
            dataTypes: "json",
            success: function(json){

                $("textarea.content").val(json["content"]);
                $.cookie("currentText", json["content"]);


                $("textarea.content").attr("prop-item", "");
                $("textarea.note").val("");

                $("textarea.content").unmask();

            }
        });

        return false;
    });

    $(".super-editor").click(function(){

                $("textarea.content").mask();
         $.ajax({
            type: 'GET',
            url: $(this).attr("itemprop"),
            dataTypes: "json",
            success: function(json){

                $("textarea.content").val(json["content"]);
                $.cookie("currentText", json["content"]);

                $("textarea.content").attr("prop-item", "/admin/ajax/sheet/" + json["id"] + "/content/update");
                $("textarea.note").val(json["note"]);

                $("textarea.content").unmask();

            }
        });

        return false;
    });

    $(".delete").click(function(){

        $("#browser").mask();
        var answer = confirm("Delete item ?");

        if (answer){
            var $li = $(this).parent();

            $.ajax({
                type: 'POST',
                url: $(this).attr("itemprop"),
                //data: data,
                success: function(){
                    $li.remove();
                    $("#browser").unmask();
                    refresh_tree();
                }
            });
        }

        return false;
    });

    $(".edit").click(function(){

         $('#myModal').modal();

            ajaxSend($(this).attr("itemprop"), "#ajax_form", "GET");

        return false;
    });

    $(".new").click(function(){

        $('#myModal').modal();

        ajaxSend($(this).attr("itemprop"), "#ajax_form", "GET");

        return false;
    });



    $(".publish").click(function(){

            $(this).hide();
            $(".unpublish", $(this).parent()).show().css("background", "greenyellow");

            ajaxSend($(this).attr("itemprop"), null, "GET");


    });

    $(".unpublish").click(function(){
            $(this).hide();
            $(".publish", $(this).parent()).show().css("background", "white");

            ajaxSend($(this).attr("itemprop"), null, "GET");
    });

    ajaxForm();
    //$(".track-node").expand);
}

function ajaxSend(item, id, method){

    if(! item) return

    if(! method){
        method = "GET";
    }

    if(! id){
        id = false;
    }

    if(id){
        $(id).mask();
    }

    $.ajax({
        type: method,
        url: item,
        //data: data,
        success: function(html){
            if(id){
                $(id).html(html);

                $(id).unmask();
            }

        }
    });
}

function ajaxForm(){

    $('#myModal').bind('keypress', function(e) {

        if(e.charCode == 13) {
            e.preventDefault();
        }

    });

    $(".save.btn-primary")
    $("#myModal .btn-primary").unbind("click");
    $("#myModal .btn-primary").bind("click", function(){

        $("#myModal form").submit(function(){
            return false;
        });

        $("#myModal form").mask("Waiting...");

        var dataForm = $("#myModal form").serializefiles();

        $.ajax({
            url: $("#myModal form").attr('action'),
            type: $("#myModal form").attr('method'),
            data: dataForm,
            dataType: 'html',

            cache: false,
            contentType: false,
            processData: false,

            success: function(html, response) {


                $("#ajax_form").html(html);

                $("#myModal form").unmask("Waiting...");
                //make_tree();
                refresh_tree();

                if(response == "success"){
                    $(".btn-close").click();
                }

            }
        });
        return false;

    });

}