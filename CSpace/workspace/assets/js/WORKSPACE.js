var WORKSPACE = (function(){
  var that = {};
  var all_tags = [];
  var lunr_index;
  var cur_lunr_id = 0;
  var only_mine;
  var userID;
  var searchRecordTimer;//only record search actions every second, to avoid constant ajax calls

  that.init = function(PAGE, feed_data, at, uid, om){
    lunr_index = lunr(function () {
      this.field('title', {boost: 10})
      this.field('body', {boost: 5})
      this.field('url')
      this.ref('id')
    });
    userID = uid;
    only_mine = om;
    for(var i = 0; i < at.length; i++){
      all_tags.push(at[i].name);
    }
    displayFeed(feed_data, $("#feed"));
    initEventListeners();
    recordAction("page", PAGE);
  }

  function recordAction(action, value, cb){
    console.log("Recording action workspace:" + action + " = " + value);
    if(value === undefined){
      value = "";
    }
    $.ajax({
      url : "../services/insertAction.php",
      data : {
        action : "workspace:" + action,
        value : value
      },
      complete: function(){
        if(cb){
          cb.call();
        }
      }
    });
  }
  function displayServiceError(msg){
    alert(msg + " Please contact developers for assistance.");
  }

  function displayBookmark(bookmark_data, root){
    var d = bookmark_data;
    var ed = $.extend({}, d); //extended data
    var url = ed["url"];
    ed["pretty_url"] = url.length > 150 ? url.substring(0,150) + "..." : url;
    ed["pretty_date"] = prettyDate(ed["date"] + "T" + ed["time"]);
    ed["real_date"] = realDate(ed["date"] + "T" + ed["time"]);
    ed["label"] = "Bookmark";
    ed["tags"] = ed["tagList"] ? ed["tagList"].split(",") : [];
    ed["all_tags"] = all_tags;
    ed["editable"] = ed["userID"] == userID;
    ed["lunr_id"] = cur_lunr_id;

    //make searchable
    lunr_index.add({
      id: cur_lunr_id,
      title: ed["title"],
      body: ed["note"],
      url: ed["url"]
    });
    var new_el = $(tmpl("bookmark_template", ed));
    root.append(new_el);
    if(!d["snippets"] || d["snippets"].length == 0){
      new_el.find(".bookmark-related").hide();
    } else {
      displayFeed(d["snippets"], $(new_el).find(".bookmark-snippets"));
    }
    cur_lunr_id++;
  }

  function displayPage(page_data, root){
    var d = page_data;
    var ed = $.extend({}, d); //extended data
    var url = ed["url"];
    ed["pretty_url"] = url.length > 150 ? url.substring(0,150) + "..." : url;
    ed["pretty_date"] = prettyDate(ed["date"] + "T" + ed["time"]);
    ed["real_date"] = realDate(ed["date"] + "T" + ed["time"]);
    ed["label"] = "Page";
    ed["editable"] = ed["userID"] == userID;
    ed["lunr_id"] = cur_lunr_id;
    root.append(tmpl("page_template", ed));
    lunr_index.add({
      id: cur_lunr_id,
      title: ed["title"],
      url: ed["url"]
    });
    cur_lunr_id++;
  }

  function displaySnippet(snippet_data, root){
    var d = snippet_data;
    var ed = $.extend({}, d); //extended data
    ed["pretty_date"] = prettyDate(ed["date"] + "T" + ed["time"]);
    ed["real_date"] = realDate(ed["date"] + "T" + ed["time"]);
    ed["shortened_snippet"] = ed["snippet"].length > 50 ? ed["snippet"].substring(0,50) + "..." : ed["snippet"];
    ed["label"] = "Snippet";
    ed["editable"] = ed["userID"] == userID;
    ed["lunr_id"] = cur_lunr_id;
    root.append(tmpl("snippet_template", ed));
    lunr_index.add({
      id: cur_lunr_id,
      title: ed["title"],
      body: ed["snippet"],
      url: ed["url"]
    });
    cur_lunr_id++;
  }

  function displaySearch(search_data, root){
    var d = search_data;
    var ed = $.extend({}, d); //extended data
    ed["pretty_date"] = prettyDate(ed["date"] + "T" + ed["time"]);
    ed["real_date"] = realDate(ed["date"] + "T" + ed["time"]);
    ed["label"] = "Search";
    ed["editable"] = ed["userID"] == userID;
    ed["lunr_id"] = cur_lunr_id;
    root.append(tmpl("query_template", ed));
    lunr_index.add({
      id: cur_lunr_id,
      title: ed["title"],
      body: ed["query"]
    });
    cur_lunr_id++;
  }

  function displaySource(source_data, root){
    var d = source_data;
    var ed = $.extend({}, d); //extended data
    ed["label"] = "Source";
    ed["editable"] = ed["userID"] == userID;
    ed["lunr_id"] = cur_lunr_id;
    var new_el = $(tmpl("source_template", ed));
    root.append(new_el);
    if(d["bookmarks"].length == 0){
      new_el.find(".bookmarks_heading").hide();
    } else {
      displayFeed(d["bookmarks"], $(new_el).find(".bookmarks"));
    }
    if(d["snippets"].length == 0){
      new_el.find(".snippets_heading").hide();
    } else {
      displayFeed(d["snippets"], $(new_el).find(".snippets"));
    }
    lunr_index.add({
      id: cur_lunr_id,
      title: ed["source"]
    });
    cur_lunr_id++;
  }

  function displayFeed(feed_data, root){
    var prev_search = null;
    for(var i = 0; i < feed_data.length; i++){
      var t = feed_data[i]["type"];
      var d = feed_data[i]["data"];
      if(d.hasOwnProperty("userID") && d["userID"] != userID && only_mine){
        continue;
      }
      switch(t){
        case "bookmark":
          displayBookmark(d, root);
          break;
        case "page":
          displayPage(d, root);
          break;
        case "snippet":
          displaySnippet(d, root);
          break;
        case "search":
          if(prev_search && prev_search["query"] == d["query"]){
            continue;
          }
          displaySearch(d, root);
          prev_search = d;
          break;
        case "source":
          displaySource(d, root);
          break;
      }
    }
  }

  function initEventListeners(){
    $("#project_selection").on("change", function(e){
      window.location = $(this).find("option:selected").attr("data-url");
    })
    $("#only_mine").on("change", function(e){
      var val = $(this).prop("checked");
      var url = $(this).attr("data-to");
      recordAction("only mine", val, function(){
        window.location = url;
      });
    });

    $("#tag_filter").on("change", function(e){
      var url = $(this).val();
      var tag = $(this).find("option:selected").html();
      recordAction("tag filter", tag, function(){
        window.location = url;
      });
    });

    $("#sorting").on("change", function(e){
      var url = $(this).val();
      var text = $(this).find("option:selected").html();
      recordAction("sorting", text, function(){
        window.location = url;
      });
    });

    $("#searchbar_input").on("keyup", function(e){
      var text = $(this).val().trim();
      if(text == ""){
        $("#feed li").removeClass("search-hidden");
        return;
      }
      var results = lunr_index.search($(this).val());
      $("#feed li").addClass("search-hidden");
      for(var i = 0; i < results.length; i++){
        var res = results[i];
        $("#feed li[data-lunr=" + res.ref + "]").removeClass("search-hidden");
      }
      if(searchRecordTimer){
          searchRecordTimer = window.clearTimeout(searchRecordTimer);
      }
      searchRecordTimer = window.setTimeout(function(){
        recordAction("search", text);
      }, 500)
    });

    $("#feed li.item-bookmark .save").on("click", function(e){
      e.preventDefault();
      var bookmarkID = $(this).attr("data-id");
      var form = $(this).parents("form");
      var tags = form.find("[name=tags]").val();
      var note = form.find("[name=note]").val();
      $.ajax({
        url: "../api/index.php",
        data: {
          "entity": "Bookmark",
          "function":"Update",
          "bookmarkID" : bookmarkID,
          "notes" : note,
          "tags" : tags
        },
        success: function(resp){
          recordAction("bookmark edit saved", bookmarkID);
          form.find(".feedback").html("Bookmark saved!").show();
        },
        error: function(){
          displayServiceError("Unknown error occurred when deleting snippet.")
        }
      });
    });

    $("#feed li .edit").on("click", function(e){
      var more = $(this).parents("li").find(".more");
      var state = $(this).attr("data-state");
      if(state == "open"){
        $(this).html("Edit");
        state = "closed";
        more.hide();
      } else {
        $(this).html("Hide Edit");
        state = "open";
        more.show();
      }
      $(this).attr("data-state", state);
      e.preventDefault();
    });

    $("#feed li.item-source .related").on("click", function(e){
      var more = $(this).parents("li").find(".related-section");
      var source_name = $(this).parents("li").find(".source_name").html();
      var state = $(this).attr("data-state");
      if(state == "open"){
        recordAction("source closed", source_name);
        $(this).html("Show related bookmarks and snippets");
        state = "closed";
        more.hide();
      } else {
        recordAction("source opened", source_name);
        $(this).html("Hide related bookmarks and snippets");
        state = "open";
        more.show();
      }
      $(this).attr("data-state", state);
      e.preventDefault();
    });
    $("#feed li.item-bookmark > .top .bookmark_link").on("click", function(e){
      var bookmarkID = $(this).parents("li").attr("data-bookmarkID");
      recordAction("bookmark link click", bookmarkID);
    });
    $("#feed li.item-snippet > .top .snippet_link").on("click", function(e){
      var snippetID = $(this).parents("li").attr("data-snippetID");
      recordAction("snippet link click", snippetID);
    });
    $("#feed li.item-search > .top .query_link").on("click", function(e){
      var queryID = $(this).parents("li").attr("data-queryID");
      recordAction("query link click", queryID);
    });
    $("#feed li.item-bookmark .bookmark-related").on("click", function(e){
      var more = $(this).parents("li").find(".bookmark-related-section");
      var state = $(this).attr("data-state");
      var bookmarkID = $(this).parents("li").attr("data-bookmarkID");
      if(state == "open"){
        recordAction("bookmark closed", bookmarkID);
        $(this).html("Show related snippets");
        state = "closed";
        more.hide();
      } else {
        recordAction("bookmark opened", bookmarkID);
        $(this).html("Hide related snippets");
        state = "open";
        more.show();
      }
      $(this).attr("data-state", state);
      e.preventDefault();
    });
    initDeleteListeners();
  }

  function initDeleteListeners(){
    $(".item-bookmark > .sub > .sub-right .delete").on("click", function(e){
      e.preventDefault();
      var id = $(this).attr("data-id");
      var item = $(this).parents(".item-bookmark");
      //send ajax request
      $.ajax({
        url: "../api/index.php",
        data: {
          "entity": "Bookmark",
          "function":"Delete",
          "bookmarkID" : id
        },
        success: function(resp){
          recordAction("bookmark delete", id);
          item.fadeOut(500, function(){item.detach();});
        },
        error: function(){
          displayServiceError("Unknown error occurred when deleting bookmark.")
        }
      });
    });

    $(".item-snippet .delete").on("click", function(e){
      e.preventDefault();
      var id = $(this).attr("data-id");
      var item = $(this).parents(".item-snippet");
      //send ajax request
      $.ajax({
        url: "../api/index.php",
        data: {
          "entity": "Snippet",
          "function":"Delete",
          "snippetID" : id
        },
        success: function(resp){
          recordAction("snippet delete", id);
          item.fadeOut(500, function(){item.detach();});
        },
        error: function(){
          displayServiceError("Unknown error occurred when deleting snippet.")
        }
      });
    });

    $(".item-page .delete").on("click", function(e){
      e.preventDefault();
      var id = $(this).attr("data-id");
      var item = $(this).parents(".item-page");
      //send ajax request
      $.ajax({
        url: "../api/index.php",
        data: {
          "entity": "Page",
          "function":"Delete",
          "pageID" : id
        },
        success: function(resp){
          recordAction("page delete", id);
          item.fadeOut(500, function(){item.detach();});
        },
        error: function(){
          displayServiceError("Unknown error occurred when deleting page.")
        }
      });
    });

    $(".item-search .delete").on("click", function(e){
      e.preventDefault();
      var id = $(this).attr("data-id");
      var item = $(this).parents(".item-search");
      //send ajax request
      $.ajax({
        url: "../api/index.php",
        data: {
          "entity": "Query",
          "function":"Delete",
          "queryID" : id
        },
        success: function(resp){
          recordAction("search delete", id);
          item.fadeOut(500, function(){item.detach();});
        },
        error: function(){
          displayServiceError("Unknown error occurred when deleting query.")
        }
      });
    });
  }
  return that;
}());
