

var $lastWord = "";
var words = [];
var $memory = [];
var $click = false;
var $page = 0;



$(".rhyme, .definition, .synonym, .scrabble, .anagram").dblclick(function(a){

    var sel = window.getSelection().anchorNode.data.substr(window.getSelection().anchorOffset, window.getSelection().focusOffset - window.getSelection().anchorOffset);

    getResources(sel);
});


$(".main_tab5").click(function(){

    var $text = $(".content").val();

    var $replace = [",", '"', ";", "!", "?", ".", " ", "'"];

    for (var i=0; i< $replace.length; i++) {
        $text.replace($replace[i], " ");
    }

    $.ajax({
        type: "POST",
        url: "/lab/analyse/corpus",
        data: {
            text: $text
        },
        success: function(data){

            var $s = "";
            for(var index in data){
                $s += index + ' : ' + data[index] + '  <br />';
            }
            $(".analysis").html($s);

        },
        dataType: "json"
    });
});

$('#state').bind('keypress', function(e) {

    if(e.charCode == 13) {
        wikipediaSearch($('#state').val());
    }

});

$('#state').autocomplete({
    serviceUrl: "/admin/lab/wikipedia/list",
    minChars:3,
    deferRequestBy: 200,
    onSelect: function(value){
        wikipediaSearch(value);

    }
});



$(".previous").click(function(){

    $page = $page-1;
    $.cookie("page", $page);
    $(".tools").hide();

    $("textarea.content").addClass("right_shadow");
    $("textarea.content").addClass("bigger");
    $("textarea.content").removeClass("left_shadow");

    $(".plan").css("background", "none");
    $(".plan").show();

    $(".previous").hide();
    $(".next").show();
//$(".content-area").css("float", "right");

});
$(".next").click(function(){

    $page = $page+1;

    $.cookie("page", $page);

    $(".next").hide();
    $(".previous").show();

    $("textarea.content").removeClass("bigger");
    $("textarea.content").removeClass("right_shadow");
    $("textarea.content").addClass("left_shadow");

    $(".tools").show();
    $(".plan").hide();
});

$(".previous").hide();



 $(".word-search").click(function(){

    var $textarea = $("textarea.content");
    var selStart = $("textarea.content")[0].selectionStart;
    var selEnd   = $("textarea.content")[0].selectionEnd;

    var sel = $textarea.val().substr(selStart, selEnd - selStart);

    getResources(sel);
    return false;
});

$(".sheet_align_center").click(function(){
    $("textarea.content").css("text-align", "center");
});
$(".sheet_align_left").click(function(){
    $("textarea.content").css("text-align", "left");
});
$(".sheet_align_right").click(function(){
    $("textarea.content").css("text-align", "right");
});

 $(".sheet_save").click(function(){

    var $url = $("textarea.content").attr("prop-item");

    if(! $url){
        alert("aucun fichier n'est chargé !");
        return;
    }

    $("textarea.content").mask();

    $.ajax({
        type: 'POST',
        url: $url,
        data: {
            content: $("textarea.content").val(),
            note: $("textarea.note").val()
        },
        success: function(){
            $(".sheet_save i").removeClass("icon-ok-circle");
            $(".sheet_save i").addClass("icon-ok-sign");
            $("textarea.content").unmask();
        }
    });
});

$(".content").keydown(function() {
    if($(".sheet_save i").hasClass("icon-ok-sign")){
        $(".sheet_save i").removeClass("icon-ok-sign");
        $(".sheet_save i").addClass("icon-ok-circle");
    }
});

$(".content").keydown(function(event) {
    $.cookie("currentText", event.srcElement.value);
});

$(".icon-resize-full").click(function(){

    $('#modal_full_screen').modal();
    $("#modal_full_screen .modal-body").html($(".super-tool"));
    $(".super-tool").addClass("resize_tool");

    $(".super-tool i.icon-resize-full").hide();
});

$("#modal_full_screen").on("hidden", function(){
    $(".super-tool").removeClass("resize_tool");
    $(".literator").append($(".super-tool"));
    $(".super-tool i.icon-resize-full").show();
});

make_tree();

$("textarea.content").val($.cookie("currentText"));

if($.cookie("page") == 1){
    $(".next").click();
}