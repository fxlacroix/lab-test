
$(document).ready(function() {


    $('ul.sommaire a').click(function(){

        var $myDiv = $(".chapter-"+$(this).attr("class")).parent().parent().parent();

        var $url = "#/page/" + (parseInt($myDiv.attr("class").replace(/b-page b-page-/, "").replace(/(\d+) .*/, "$1")) + 1);

        $(this).attr("href", $url);
    });
});
