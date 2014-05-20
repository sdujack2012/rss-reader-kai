var loading = false;
var img = new Image();
var initialized = false;
$(function() {
    initializeApp();
});
/**
 * initialize all the resource needed for the application
 */
function initializeApp() {
    img.src = "./images/updating.gif";
    $("#error").html("");
    $("#feedlist").html("");
    $("#loginbutton").click(function() {
        $("#error").html("");
        username = $("#username_login").val();
        password = $("#password_login").val();
        login(username, password);
    });
    $("#registerbutton").click(function() {
        $("#error").html("");
        username = $("#username_register").val();
        password = $("#password_register").val();
        register(username, password);
    });
    $("#registerlink").click(function() {
        $("#error").html("");
        $("#login").hide();
        $("#register").show();
    });
    $("#backLogin").click(function() {
        $("#error").html("");
        $("#login").show();
        $("#register").hide();
    });
    $("#user_panel").hide();
    $("#login").hide();
    $("#register").hide();
    $("#rssloading").hide();
    $("#cover").hide();
    //addLogoutListener();
    //addNewFeedListener();
    addNewFeedFormListener();
    refreshPage();
    $("#addFeedForm").dialog({modal: true, autoOpen: false, height: 180, width: 320});
}

/**
 * handle user registeration
 */
function register(username, password) {
    $("#register").hide();
    $("#loading").show();
    $.ajax({
        type: "post",
        url: "main.php",
        data: "username=" + username + "&password=" + password + "&action=register",
        success: function(data) {
            retriveData(data)
        }
    });
}

/**
 * handle user login
 */
function login(username, password) {
    $("#login").hide();
    $("#loading").show();
    $.ajax({
        type: "post",
        url: "main.php",
        data: "username=" + username + "&password=" + password + "&action=login",
        success: function(data) {
            retriveData(data)
        }
    });
}

/**
 * get feed list in json string from the server and present it to user
 */
function retriveData(data) {
    $("#loading").hide();
    var response = json_decode(data);
    if (!response) {
        alert("can't retrieve user information, try later");
        $("#login").show();
        $("#user_panel").html("");
        return;
    }

    if (response.status == "good") {
        var panel_info = "Welcome " + response.info.username;
        panel_info += " <input type='button' id='logout' value='Logout' />";
        panel_info += "<br /><br /><br /><input type='button'  id='addfeed' value='add New Feed' />";
        var feeds = response.info.feedlist;
        var feedlist = "<table id='feeds'>";
        for (var i = 0; i < feeds.length; i++) {
            var name_id = "feedname" + i;
            var url_id = "feedurl" + i;
            var img_id = "img" + i;
            feedlist += "<tr><td>";
            feedlist += "Feed Name:<input type='text'  id='" + name_id + "' value='" + feeds[i].feedname + "' /><br />";
            feedlist += "Feed Url:<input type='text'  id='" + url_id + "' title='" + feeds[i].url + "' value='" + feeds[i].url + "' /><br />";
            feedlist += "<input type='button' value='Modify' onclick=modifyFeed('" + feeds[i].feedname + "','" + feeds[i].url + "','" + name_id + "','" + url_id + "','" + img_id + "'); />&nbsp;&nbsp;";
            feedlist += "<input type='button' value='Delete' onclick=deleteFeed('" + feeds[i].feedname + "','" + feeds[i].url + "'); />&nbsp;&nbsp;";
            feedlist += "<input type='button' value='Read' onclick=readRss('" + url_id + "'); />&nbsp;&nbsp;";
            feedlist += "<img id='" + img_id + "' class='updating' width='20px' height='20px' src='" + img.src + "'>";
            feedlist += "</td></tr>";
        }

        feedlist += "</table>";
        $("#user_panel").html(panel_info);

        $("#user_panel").show();
        $("#feedlist").html(feedlist);
        $(".updating").hide();
        $("#feeds").tooltip();
        addLogoutListener();
        addNewFeedListener();
    }
    else {
        $("#login").show();
        $("#user_panel").html("");
        $("#rsscontentlist").html("");
        if (initialized == true) {
            $("#error").html(response.info);
        }
        else {
            initialized = true;
        }

    }
}



/**
 * add click listener to the logout button
 */
function addLogoutListener() {
    $("#logout").click(function() {
        $.ajax({
            type: "get",
            url: "logout.php",
            success: function(data) {
                initializeApp();
                $("#error").html("Logout Successfully");
                $("#rsscontentlist").html("");
            }
        });
    });

}
/**
 * add click listener to the 'add new feed' button
 */
function addNewFeedListener() {
    $("#addfeed").click(function() {
        $("#addFeedForm").dialog("open");
    });

}

/**
 * add click listener to elements in 'add New Feed Form'
 */
function addNewFeedFormListener() {
    $("#addfeedbutton").click(function() {
        var feedname = $("#newFeedName").val();
        var feedurl = $("#newFeedURL").val();
        if (isURL(feedurl) && feedname != "") {
            $.ajax({
                type: "post",
                url: "addNewFeed.php",
                data: "feedname=" + feedname + "&feedurl=" + feedurl,
                success: function(data) {
                    $("#addFeedForm").dialog("close");
                    $("#newFeedName").val("");
                    $("#newFeedURL").val("");
                    var response = json_decode(data);
                    if (!response) {
                        alert("Unkown Error");
                        return;
                    }
                    alert(response.info);
                    refreshPage();
                }
            });
        }
        else {
            alert("Please enter valid Rss url and non-empty name!");
        }

    });

    $("#closefeedbutton").click(function() {
        $("#addFeedForm").dialog("close");
    });

}

/**
 * handle 'modify feed event'
 */
function modifyFeed(feedname, feedurl, name_id, url_id, img_id) {
    var newfeedname = $("#" + name_id).val();
    var newfeedurl = $("#" + url_id).val();
    if (!isURL(newfeedurl)) {
        alert("Dont input invalid url");
        refreshPage();
        return;
    }
    $("#" + img_id).show();
    $.ajax({
        type: "post",
        url: "modifyFeed.php",
        data: "feedname=" + feedname + "&feedurl=" + feedurl + "&newfeedname=" + newfeedname + "&newfeedurl=" + newfeedurl,
        error: function(data) {
            $("#" + img_id).hide();
        },
        success: function(data) {
            $("#" + img_id).hide();
            var response = json_decode(data);
            if (!response) {
                alert("Unkown Error");
                return;
            }
            if (response.status == "good") {
                alert(response.info);
            }
            refreshPage();
        }
    });
}

/**
 * handle 'delete feed event'
 */
function deleteFeed(feedname, feedurl) {
    if (confirm('Do you really want to delete the feed?')) {
        $("#cover").show();
        $.ajax({
            type: "post",
            url: "deleteFeed.php",
            data: "feedname=" + feedname + "&feedurl=" + feedurl,
            success: function(data) {
                refreshPage();
            },
            error: function(data) {
                $("#cover").hide();
            }
        });
    }
}

/**
 * read parsed rss feed result in a json string and render it with html marks then present it to user
 */
function readRss(url_id) {
    if (loading) {
        alert("A request is being process, please try later");
        return;
    }
    loading = true;
    var timer = setTimeout(function() {
        ajax.abort();
        loading = false;
        $("#rssloading").hide();
        alert("Request Timeout");
    },
            15000);
    $("#rssloading").show();
    $("#rsscontentlist").html("");
    var feedurl = $("#" + url_id).val();
    var ajax = $.ajax({
        type: "post",
        url: "readRss.php",
        data: "feedurl=" + feedurl,
        success: function(data) {
            renderRss(data);
        },
        complete: function(data) {
            loading = false;
            $("#rssloading").hide();
            clearTimeout(timer);
        }
    });
}

/**
 * update user information and feed list in the page
 */
function refreshPage() {
    $("#cover").show();
    $.ajax({
        type: "POST",
        url: "main.php",
        success: function(data) {
            retriveData(data);
        },
        complete: function(data) {
            $("#cover").hide();
        }
    });
}

/**
 * regular expression for validating a legal url
 */
function isURL(str) {
    //regular expression to check if a given string is valid url
    var Exp = /^http(s)?:////([\w-]+\.)+[\w-]+(\/[\w- .\/?%&=]*)?/;  
    var objExp = new RegExp(Exp);
    if (objExp.test(str) == true) {
        return true;
    } else {
        return false;
    }
}
function json_decode(data) {
    var response;
    try {
        response = eval('(' + data + ')');
    }
    catch (e) {
        return false;
    }
    return response;
}
/**
 * render parsed rss feed result to the browser
 */
function renderRss(data) {
    var response = json_decode(data);
    if (!response) {
        alert("Unkown Error");
        return;
    }
    if (response.status == "good") {
        response = response.info;
        var feedinfo = "<table id='feedinfo'>"
        for (var i = 0; i < response.length; i++) {
            feedinfo += "<tr><td>";
            feedinfo += "<div class='title'>" + response[i].title + "</div>";
            feedinfo += "<div class='link'><a  target='_blank' href='" + response[i].link + "'>" + response[i].link + "</a></div>";
            feedinfo += "<div class='description'>" + response[i].description.substr(0, 200) + "</div>";
            feedinfo += "<hr />";
            feedinfo += "</td></tr>";
        }
        feedinfo += "</table>";
        $("#rsscontentlist").html(feedinfo);
    }
    else if (response.info == "rss") {
        alert("Can't read the rss");
    }
    else if (response.info == "unkown") {
        refreshPage();
    }

}