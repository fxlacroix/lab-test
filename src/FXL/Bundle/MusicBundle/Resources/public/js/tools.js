
function blogart_analytics(){

    _gaq.push(['_setAccount', 'UA-314658-7']);
    _gaq.push(['_trackPageview', $("title").html()]);

    //console.log(self.location.href);
    //console.log($("title").html());

}

function refresh_tree(load, projectId){

    if(typeof projectId == 'undefined'){

        projectId = $(".breadcrumb select").val();
    }

    if(typeof projectId == 'undefined'){

        projectId = $(".breadcrumb select").val();
    }

    $("#admin_tree").mask("Waiting...");

    $.ajax({
        url: "/admin/ajax/tree/"+projectId,
        type: "GET",
        dataType: 'html',
        success: function(html) {
            $("#admin_tree").unmask();
            $("#admin_tree").html(html);

            if(load){

                //console.log($(".track-node > .edit"));
                $(".edit").first().click();
                //$(".track-node").expand();
            }
        }
    });
}

function make_tree(projectId){

    $("#browser").treeview({
		cookieId: "treeview-admin",
		collapsed: true,
        persist: "cookie",
		//unique: true

    });

    $("#browser").attr("itemprop", true);

    $(".refresh_tree").click(function(){

        refresh_tree(projectId)
    });

    $(".delete").click(function(){

        $("#browser").mask();

        var answer = confirm("Delete item ?")
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

         $("#ajax_form").mask();

         $.ajax({
            type: 'GET',
            url: $(this).attr("itemprop"),
            //data: data,
            success: function(html){

                $("#ajax_form").html(html);

                $("#ajax_form").unmask();

            }
        });

        return false;
    });

    $(".new").click(function(){

        //$('#ajax_form').load($(this).attr("itemprop"));

         $("#ajax_form").mask();
        $.ajax({
            type: 'GET',
            url: $(this).attr("itemprop"),
            //data: data,
            success: function(html){

                $("#ajax_form").html(html);

                $("#ajax_form").unmask();

            }
        });

        return false;
    });

    //$(".track-node").expand);
}

Array.prototype.inArray = function(p_val) {
    var l = this.length;
    for(var i = 0; i < l; i++) {
        if(this[i] == p_val) {
            return true;
        }
    }
    return false;
}

function slugify(text) {
	text = text.replace(/[^-a-zA-Z0-9,&\s]+/ig, '');
	text = text.replace(/-/gi, "_");
	text = text.replace(/\s/gi, "-");
	return text;
}

var myTextExtraction = function(node)
{
    // extract data from markup and return it
    return node.childNodes[0].childNodes[0].innerHTML;
}
