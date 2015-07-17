String.prototype.contains = function(it) {
    return this.indexOf(it) != -1;
};

String.prototype.fulltrim=function(){
    return this.replace(/(?:(?:^|\n)\s+|\s+(?:$|\n))/g,'').replace(/\s+/g,' ');
};

Array.prototype.contains = function (element) {
    for (var i = 0; i < this.length; i++) {
        if (this[i] == element) {
            return true;
        }
    }
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

function initAccordeon(id){

    return '<div class="accordion" id="accordion'+ id +'"><div class="accordion-group">';

}
function closeAccordeon(){

    return '</div>';
}


function initAccordeonGroup(){

    return ' <div class="accordion-group">';
}
function closeAccordeonGroup(){
    return '</div>';
}

function addAccordeonHeading(id, heading){

    return '<div class="accordion-heading"><a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion'+id+'" href="#collapse'+id+'">'+heading+'</a></div>';

}
function addAccordeonBody(id, body){
    return '<div id="collapse'+id+'" class="accordion-body collapse"><div class="accordion-inner">'+body+'</div></div>';
}


function recoverTab($id, exist){

    /*
        $(".tab-pane.sub ." + $id).parent().show();
        $(".nav-tabs.sub .tab-" + $id).parent().show();

         $(".nav-tabs.sub .tab-" + $id).tab('show');
         */

    if(! exist){
        $(".nav-tabs.sub .tab-" + $id).css("color", "red");
        $(".nav-tabs.sub .tab-" + $id).css("display", "none");
    }else{
        $(".nav-tabs.sub .tab-" + $id).css("display", "block");
        $(".nav-tabs.sub .tab-" + $id).css("color", "green");

        if(!$click){
            $(".nav-tabs.sub .tab-" + $id).click();
            $click = true;
        }
    }
}

function launchAjax($id, $url, $lastWord) {

    $('.' + $id).css("background", "#eee");
    $.ajax({
        url: $url + $lastWord,
        dataType: "html",
        success: function(data) {

            recoverTab($id, data != "<ul></ul>");

            $('.' + $id).html(data);
            $('.' + $id).css("background", "white");

        }
    });
}

function launchAjaxJson($id, $url, $lastWord) {

    $('.' + $id).css("background", "#eee");
    $.ajax({
        url: $url + $lastWord,
        dataType: "json",
        success: function(data) {

            recoverTab($id, data.length);

            var synonyms = "";

            for(i=0;i < data.length; i++){

                if(data.length > 15){

                    synonyms += '<li><div>'+ data[i] +'</div></li>';
                } else {

                    synonyms += '<div>'+ data[i] +'</div>';
                }
            }

            $('.' + $id).html(synonyms);
            $('.' + $id).css("background", "white");
        }
    });
}

function launchSearch($lastWord){

    if(! $lastWord) return;

    var $replace = [",", ";", "!", "?", ".", " ", "'"];

    for (var i=0; i< $replace.length; i++) {
        $lastWord.replace($replace[i], " ");
    }

    $lastWord.fulltrim();

    $(".motif").html($lastWord);

    launchAjax('phonetic', '/admin/lab/phonetic/', $lastWord);

    launchAjax('definition', '/admin/lab/definition/', $lastWord);

    launchAjaxJson('synonym', '/admin/lab/synonym/', $lastWord);
    launchAjaxJson('rhyme', '/admin/lab/rhyme/', $lastWord);
    launchAjaxJson('scrabble', '/admin/lab/scrabble/', $lastWord);
    launchAjaxJson('anagram', '/admin/lab/anagram/', $lastWord);

    launchAjax('image', '/admin/lab/image/', $lastWord);

//?
//launchAjax('analysis', '/lab/analysis/', $lastWord);
//launchAjax('syllable', '/lab/syllable/', $lastWord);
}

function getResources(sel){

    $click = false;

    $(".nav.sub li a").hide();
    //$(".nav.sub li a").css("color", "#333");

    launchSearch(sel);

    if(! $memory.contains(sel) ) {

        $(".memory-item").append("<li><a href='#'>"+ sel +"</a></li>");

        $memory.push(sel);
    }

    $(".memory-item li").click(function(){
        launchSearch($("a", this).html());
    });

    $(".memory-item li").bind("contextmenu", function(e) {
        $(this).remove()
        return false;
    });
}


function wikipediaSearch(value){

    $.ajax({
        dataType: "json",
        url: "/admin/lab/wikipedia/"+value,
        success: function(data){

            if(typeof this.counter == 'undefined'){
                this.counter = 0;
            }else{
                this.counter++;
            }

            var display = initAccordeon(this.counter);

            for(var i in data){

                if(typeof data[i]['label']!='undefined' && typeof data[i]['value']!='undefined'){

                    display += initAccordeonGroup();
                    display += addAccordeonHeading(i, data[i]['label']);
                    display += addAccordeonBody(i, data[i]['value']);
                    display += closeAccordeonGroup();
                }
            }

            display += closeAccordeon();

            $(".wikipedia-definition").html(display);
        }
    });
}

