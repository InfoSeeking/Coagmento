<?php
	require_once("header.php");
?>
    	<!-- Content region -->

    	<div id="main-content" class="collaboratory_scenarios">
    	  
    	  <div class="row-fluid wrapper">
    	    <div class="span12">
    	      <h1 class="page-title">Collaboratory Scenarios</h1>
    	    </div>
    	  </div>
    	  
    	  <div class="row-fluid wrapper">
                <div class="step" id="step1">
                    <h2>Overview</h2>
                    <p>You are running a research study in which you wish to see how people collaborate on a given task.</p>
                    <p>The task is defined as follows:</p>
                    <blockquote>
                        Describe the current state of Australian government as well as the public's opinion.
                    </blockquote>
                    <p>The three participants <b>Mary</b>, <b>Elizabeth</b>, and <b>George</b> are all told to collaborate together by talking and to bookmark important pages and capture important information.</p>
                    <p>They are all given computers with the Firefox plugin from Coagmento Collaboratory installed shown below</p>
                    <img src="img/collaboratory-scenarios/1.png" />
                </div>

                <div class="step" id="step2">
                    <h2>Setup</h2>
                    <p>Each of the participants is told to keep a page open so they can share what they are collecting and facilitate collaboration. This page shows all snippets, bookmarks, and history of the other participants</p>
                    <img src="img/collaboratory-scenarios/2.png" />
                </div>


                <div class="step" id="step3">
                    <h2>In Action</h2>
                    <p>Now let's say you are the acting as <b>Mary</b>. After opening a new tab you wish to search for something on a search engine. Type a related search into the search box:</p>
                    Search&nbsp;&nbsp;<input type="text" id="searchQuery" />
                </div>

                <div class="step" id="step4">
                    <h2>Results</h2>
                    <p>As you can see, the search you just made now appears on the data page and others in the group can see as well.</p>
                    <div class="outputContainer">
                        <img src="img/collaboratory-scenarios/3.png?v2" />
                        <div class="output"></div>
                    </div>
                </div>

                <div class="step" id="step5">
                    <h2>Conclusion</h2>
                    <p>After the group has done all of their searching, you can view all of the data they collected and analyze.</p>
                    <div class="outputContainer">
                        <img src="img/collaboratory-scenarios/4.png?v2" />
                        <div class="output"></div>
                    </div>
                    <br/>
                    <p>You can see how Coagmento Collaboratory has been used to easily set up a collaborative study among a group.<br/>If you would like to download Coagmento Collaboratory <a href="https://github.com/kevinAlbs/CoagmentoCollaboratory" title="Download" target="_blank">click here</a>.</p>

                </div>
    		  <!-- End of the main content -->
                <div id="controls">
                    <a id="prev">&laquo;&nbsp;Prev</a><a id="next">Next&nbsp;&raquo;</a>
                </div>
    		</div> <!-- /wrapper -->
    	</div> <!-- /main-content -->

    	<!-- End of Content region -->
    <script>
        window.addEventListener("load", function(){
            var curStep = 1;
            $("#next").on("click", function(){
                switch(curStep){
                    case 3:
                    if($("#searchQuery").val().trim() == ""){
                        alert("Not quite! Try typing in a search query into the search box.");
                        return;
                    }
                    $(".outputContainer .output").html($("#searchQuery").val().trim());
                    break;
                }
                curStep++;
                if(curStep == 5){
                    //end
                    $(this).hide();
                }
                $("#prev").show();
                $(".step").hide();
                $("#step" + curStep).fadeIn();
            });
            $("#prev").on("click", function(){
                curStep--;
                if(curStep == 1){
                    $(this).hide();
                }
                $("#next").show();
                $(".step").hide();
                $("#step" + curStep).fadeIn();
            });
        });
    </script>
<?php
	require_once("footer.php");
?>
