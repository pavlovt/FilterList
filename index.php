<?
require_once "functions.php";

$options = array("name" => "region", "multiple" => "multiple", "onchange" => "list.searchFilter()", "class" => "span12", "data-placeholder" => "Region", "operator" => "in");
$jsOptions = array("allow_single_deselect" => true);
$data = array("Africa" => "Africa", "Americas" => "Americas", "Asia" => "Asia", "Europe" => "Europe", "Oceania" => "Oceania");
$selectRegion = select_chosen($data, $url = "", $options, $jsOptions);

$options = array("name" => "relevance", "multiple" => "multiple", "onchange" => "list.searchFilter()", "class" => "span12", "data-placeholder" => "Relevance", "operator" => "in");
$jsOptions = array("allow_single_deselect" => true);
$data = array("0.5" => "0.5", "1" => "1", "1.5" => "1.5", "2" => "2");
$selectRelevance = select_chosen($data, $url = "", $options, $jsOptions);

?>
<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link href="js/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="js/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
    <link href="js/chosen/chosen.css" rel="stylesheet">

    <script src="js/jquery.183.min.js"></script>

</head>

<body>

    <div class="container">

        <div class="navbar navbar-inverse">
          <div class="navbar-inner">
            <a class="brand" href="#">Country List</a>
            <ul class="nav">
              <li class="active"><a href="#">Home</a></li>
            </ul>
          </div>
        </div>

        <div id="filterList" class="row-fluid">

            <div class="well">
                <div class="row-fluid">
                	<div class="span2"><input type="text" name="search_all" class="search span12" placeholder="Search..." data-operator="in" data-type="string"></div>
                    <form id="search_form" data-persist="garlic">
	                    <div class="span2 hide"><button class="sort btn btn-inverse span12" data-sort="caption">Sort by caption</button></div>
	                    <div class="span2"><?=$selectRelevance?></div>
	                    <div class="span2"><?=$selectRegion?></div>
	                    <div class="span1 hide">Status: <input type="checkbox" name="is_activez" ng-model="search.is_active.val" ng-true-value="1" ng-false-value="0" ng-change="filter()"></div>
                    </form>
                </div>
            </div>

            <div class="pagination pull-right">
                <ul class="pagingTop"></ul>
            </div><br><br><br>

            <ul class="list media-list"></ul>

            <div class="pagination pull-right">
                <ul class="pagingBottom"></ul>
            </div>

        </div>

        <div id="myelm" class="hide">
            <!-- A template element is needed when list is empty, TODO: needs a better solution -->
            <li id="item" class="media">
                <a class="pull-left" href="#">
                  <img class="media-object" src="data/flags/{name|concat}.png">
                </a>
                <div class="media-body">
                    <h4 class="media-heading">{name}</h4>
                    <p>Currency: {currency}</p>
                    <p>Region: {region}</p>
                </div>
            </li>
        </div>
    </div>

<script src="js/functions.js"></script>
<script src="js/hash.min.js"></script>
<script src="js/list/filterList.js"></script>

<script src="js/dust/dust-full-0.3.0.min.js"></script>

<script src="js/list/list.js"></script>
<script src="js/list/list.template.dust.js"></script>
<script src="js/list/list.paging.min.js"></script>

<script src="js/chosen/chosen.jquery.min.js"></script>


<script type="text/javascript" charset="utf-8">
var list;
$(document).ready(function() {
    //list.list.search($("[name=search_all]").val());

    // a function to use as filter in Dust.js
    // to concatenate for example Puerto Rico as Puerto_Rico
    // because the flag for this country is called Puerto_Rico.png
    // we have to use a function because we cannot include any logic in the template
    var concat = function (value) {

    	if ( (typeof value != 'undefined') && (value.length > 0) ) {
    		return value.split(" ").join("_");

    	}

    	return value;
    }

    $.extend(dust.filters, {"concat": concat});

    var o = { list: "filterList", pagingTop: "pagingTop", pagingBottom: "pagingBottom", searchForm: "search_form", engine: "dustjs", template: {name: "template", element: "#myelm"}, data: [], url: "data/countries.full.json" };
    list = new filterList(o, $);


});

</script>
    </body>
</html>