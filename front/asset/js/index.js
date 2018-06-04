/**
 * @brief manage the active class on navbar
 */
$(".navbar-item").click(function() {
	$(".active").removeClass("active");
	$(this).addClass("active");
});

/**
 * @brief manage the content section rendering
 */
$(".content-item").click(function() {
	setFromAjax($(this).text());
});

/**
 * @brief get values from ajax
 * 
 * @param r contain the query
 * 
 * @return nothing
 */
function setFromAjax(str) {
    if (str.length == 0) { 
        $(".panel-heading").text("");
        $(".panel-body").text("");
        return;
    } else {
        if (str.indexOf("ALL") > -1) {
            $(".panel-heading").text('LOADING');
            $(".panel-body").hide();
            $(".panel-body").html('<div class="loading">&nbsp;</div>');
            $(".panel-body").fadeIn();
        }
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                $(".panel-body").hide();
                try {
                    ret = JSON.parse(this.responseText);
                    $(".panel-heading").text(ret.header);
                    if (ret.body == '') {
                        ret.body = 'No data found on the API, maybe you should connect to the game with java client and grab some data.';
                    }
                    $(".panel-body").html(ret.body);
                    $(document).attr("title", "AR | "+ret.header);
                    window.history.pushState(ret.body, "AR | "+ret.header, '?watch='+ret.header+'#');
                } catch(err) {
                    console.log(ret);
                    $(".panel-heading").text('ERROR');
                    $(".panel-body").html('An error occurs regarding to saltan API json formatting.');
                    $(document).attr("title", "AR | ERROR");
                }
                $(".panel-body").fadeIn();
            }
        };
        xmlhttp.open("GET", "src/php/ajaxCall.php?q=" + str, true);
        xmlhttp.send();
    }	
}

window.history.pushState($(".panel-body").html(), "AR | "+$(".panel-heading").text(), '?watch='+$(".panel-heading").text()+'#');

window.onpopstate = function(e){
    if (e.state) {
        //console.log(e);
        $(".panel-body").html(e.state);
        $(".panel-heading").html(e.path[0].location.search.substr(7));
        $(document).attr("title", "AR | "+e.path[0].location.search.substr(7));
    }
};
