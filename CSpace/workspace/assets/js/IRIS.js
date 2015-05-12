var IRIS = (function(){
  var that = {};

  that.cluster = function(urls, num_clusters){
      //build xml
      var xml1 = "<parameters>" +
        "<requestType>cluster</requestType>" +
        "<numClusters>" + num_clusters + "</numClusters>" +
        "<resourceList>";
      var resources = "";
      for(var i = 0; i < urls.length; i++){
        resources += "<resource><id>" + i + "</id><url>" + urls[i] + "</url></resource>";
      }
      var xml = xml1 + resources + "</resourceList></parameters>";
      var cluster_results = $(".cluster_results");
      cluster_results.empty();
      cluster_results.append("Loading, please wait");
      $.ajax({
        url : "http://iris.comminfo.rutgers.edu/",
        type : "post",
        data : {
          "xmldata" : xml
        },
        success : function(resp){
          console.log(resp);
          var xdata = $($.parseXML(resp));
          var error = xdata.find("error message");
          cluster_results.empty();
          if(error.size() > 0){
            alert(error.html());
            return;
          }

          var clusters = xdata.find("cluster");

          for(var i = 0; i < clusters.size(); i++){
            var p = $("<p>Cluster: " + (i+1) + "</p>");
            var ul = $("<ul>");
            var cluster = $(clusters.get(i));
            var resources = cluster.find("resource");
            for(var j = 0; j < resources.size(); j++){
              var r = $(resources.get(j));
              if(r.find("id").html() == "-1"){
                continue;
              }
              var url = r.find("url").html();
              ul.append("<li>" + url + "</li>");
            }
            cluster_results.append(p);
            cluster_results.append(ul);
          }
        }
      });
  };

  return that;
}());
