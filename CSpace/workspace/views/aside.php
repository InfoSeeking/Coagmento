<div class="searchbar">
  <input type="text" placeholder="Search" id="searchbar_input" />
</div>

<?php
if($PAGE == "BOOKMARKS"):
?>
<div id="bookmark_filters">
  <h4>Filter by tag</h4>
  <select id="tag_filter">
  <?php
    $all_param = array(
      "bookmark_tag_filter" => false,
      "page" => "BOOKMARKS"
    );
    printf("<option value='%s' %s>All Tags</option>", gen_url($all_param), $all_param["bookmark_tag_filter"] == $current_tag ? "selected" : "");
    foreach($tag_data as $tag){
      $param = array(
        "bookmark_tag_filter" => $tag["name"],
        "page" => "BOOKMARKS"
      );
      printf("<option value='%s' %s>", gen_url($param), $param["bookmark_tag_filter"] == $current_tag ? "selected" : "");
      printf("%s</option>", $tag["name"]);
      echo "</li>";
    }
  ?>
  </select>
</div><!-- /#bookmark_filters -->
<?php
endif;
?>

<div class="sorting">
<?php
$has_sorting = false;
if($PAGE == "BOOKMARKS"):
  $has_sorting = true;
  $params = array(
    "page" => "BOOKMARKS",
    "sorting_order" => $sorting_order
  );
?>
<h4>Sort by</h4>
<select id="sorting">
  <?php
  $params["sorting"] = "timestamp";
  $params["sorting_order"] = "DESC";
  printf("<option value='%s' %s>Time saved [Newest first]</option>", gen_url($params), $params["sorting"] == $sorting && $params["sorting_order"] == $sorting_order ? "selected" : "");
  $params["sorting_order"] = "ASC";
  printf("<option value='%s' %s>Time saved [Oldest first]</option>", gen_url($params), $params["sorting"] == $sorting && $params["sorting_order"] == $sorting_order ? "selected" : "");

  $params["sorting"] = "rating";
  $params["sorting_order"] = "ASC";
  printf("<option value='%s' %s>Rating [0 to 5]</option>", gen_url($params), $params["sorting"] == $sorting && $params["sorting_order"] == $sorting_order ? "selected" : "");
  $params["sorting_order"] = "DESC";
  printf("<option value='%s' %s>Rating [5 to 0]</option>", gen_url($params), $params["sorting"] == $sorting && $params["sorting_order"] == $sorting_order ? "selected" : "");

  $params["sorting"] = "title";
  $params["sorting_order"] = "ASC";
  printf("<option value='%s' %s>Page title [A to Z]</option>", gen_url($params), $params["sorting"] == $sorting && $params["sorting_order"] == $sorting_order ? "selected" : "");
  $params["sorting_order"] = "DESC";
  printf("<option value='%s' %s>Page title [Z to A]</option>", gen_url($params), $params["sorting"] == $sorting && $params["sorting_order"] == $sorting_order ? "selected" : "");
  ?>
</select>

<?php
elseif($PAGE == "SNIPPETS"):
  $has_sorting = true;
  $params = array(
    "page" => "SNIPPETS",
    "sorting_order" => $sorting_order
  );
?>
<h4>Sort by</h4>
<select id="sorting">
  <?php
  $params["sorting"] = "timestamp";
  $params["sorting_order"] = "DESC";
  printf("<option value='%s' %s>Time saved [Newest first]</option>", gen_url($params), $params["sorting"] == $sorting && $params["sorting_order"] == $sorting_order ? "selected" : "");
  $params["sorting_order"] = "ASC";
  printf("<option value='%s' %s>Time saved [Oldest first]</option>", gen_url($params), $params["sorting"] == $sorting && $params["sorting_order"] == $sorting_order ? "selected" : "");

  $params["sorting"] = "title";
  $params["sorting_order"] = "ASC";
  printf("<option value='%s' %s>Title text [A to Z]</option>", gen_url($params), $params["sorting"] == $sorting && $params["sorting_order"] == $sorting_order ? "selected" : "");
  $params["sorting_order"] = "DESC";
  printf("<option value='%s' %s>Title text [Z to A]</option>", gen_url($params), $params["sorting"] == $sorting && $params["sorting_order"] == $sorting_order ? "selected" : "");

  $params["sorting"] = "snippet";
  $params["sorting_order"] = "ASC";
  printf("<option value='%s' %s>Snippet text [A to Z]</option>", gen_url($params), $params["sorting"] == $sorting && $params["sorting_order"] == $sorting_order ? "selected" : "");
  $params["sorting_order"] = "DESC";
  printf("<option value='%s' %s>Snippet text [Z to A]</option>", gen_url($params), $params["sorting"] == $sorting && $params["sorting_order"] == $sorting_order ? "selected" : "");


  ?>
</select>

<?php
elseif($PAGE == "SEARCHES"):
  $has_sorting = true;
  $params = array(
    "page" => "SEARCHES",
    "sorting_order" => $sorting_order
  );
?>
<h4>Sort by</h4>
<select id="sorting">
  <?php
  $params["sorting"] = "timestamp";
  $params["sorting_order"] = "DESC";
  printf("<option value='%s' %s>Time saved [Newest first]</option>", gen_url($params), $params["sorting"] == $sorting && $params["sorting_order"] == $sorting_order ? "selected" : "");
  $params["sorting_order"] = "ASC";
  printf("<option value='%s' %s>Time saved [Oldest first]</option>", gen_url($params), $params["sorting"] == $sorting && $params["sorting_order"] == $sorting_order ? "selected" : "");
  ?>
</select>

<?php endif; ?>

<div class="only_mine_container">
  <input type="checkbox" id="only_mine" <?php echo ($only_mine ? "checked" : ""); ?> data-to="<?php echo gen_url(array("only_mine" => !$only_mine)); ?>"> <label for="only_mine">Only show mine</label>
</div>

</div>
