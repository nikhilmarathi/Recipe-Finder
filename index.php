<?php
// Report simple running errors
error_reporting(E_ERROR | E_WARNING | E_PARSE);

require_once( "sparqllib.php" );

// SPARQL End-point 
$db = sparql_connect( "http://localhost:3030/recipe/query" );

if( !$db ) { print sparql_errno() . ": " . sparql_error(). "\n"; exit; }
$fa = "http://www.semanticweb.org/nikhil/ontologies/2019/10/untitled-ontology-22#";
// Define name space for your ontology
sparql_ns( "foaf",$fa );

//SPARQL Query 
// $sparql = "SELECT ?recipe 
// 	   WHERE { 
// 		?recipe foaf:name ?Recipe_name. 
// 		";
// $sparql .=	 "}";
$parm = "";
if(!is_null($_GET["x"]))
{
	$parm =$_GET["x"];
}



# Params Allergies=ALL&Chef=Sanjeev_Kapoor&Ingredient=ALL&=ALL&Rating=ALL&DDifficulty=ALL&Cuisine=ALL&Nutritional=ALL


$sparql = "SELECT  ?RecipeName ?ChefName ?Allerigic  ?Ingredients ?Duration ?Rating ?RecipeProcedure ?DifficultyLevel ?NutritionalValue
WHERE {
 # ?RN rdfs:subClassOf* ?parent .
 # ?RN foaf:Chef_name ?ChefName .
   ?recipe foaf:recipe_name  ?RecipeName.
   ?recipe foaf:allerigic_to ?Allerigic .
   ?recipe foaf:recipe_procedure ?RecipeProcedure.
   ?recipe foaf:has_ingredient ?Ingredients.
  ?recipe foaf:has_duration ?Duration .
  ?recipe foaf:has_chef_name ?ChefName.
  ?recipe foaf:has_ratings ?Rating. 
  ?recipe foaf:has_difficulty_level ?DifficultyLevel.
  ?recipe foaf:nutri_value ?NutritionalValue";


if(!is_null($_GET["Allergies"]) && $_GET["Allergies"] != 'ALL' ) 
	$sparql .=  "  FILTER (?Allerigic != foaf:".$_GET["Allergies"].")   ";

if(!is_null($_GET["Ingredient"]) && $_GET["Ingredient"] != 'ALL' ) 
	$sparql .=  " FILTER(regex(str(?Ingredients), '".$_GET["Ingredient"]."', 'i')) ";

if(!is_null($_GET["Chef"]) && $_GET["Chef"] != 'ALL' ) 
	$sparql .=  " FILTER(regex(str(?ChefName), '".$_GET["Chef"]."', 'i')) ";

if(!is_null($_GET["Duration"]) && $_GET["Duration"] != 'ALL' ) 
	$sparql .=  " FILTER(regex(str(?Duration), '".$_GET["Duration"]."', 'i')) ";

if(!is_null($_GET["Rating"]) && $_GET["Rating"] != 'ALL' ) 
	$sparql .=  " FILTER(regex(str(?Rating), '".$_GET["Rating"]."', 'i')) ";

if(!is_null($_GET["DDifficulty"]) && $_GET["DDifficulty"] != 'ALL' ) 
	$sparql .=  " FILTER(regex(str(?DifficultyLevel), '".$_GET["DDifficulty"]."', 'i')) ";

if(!is_null($_GET["Nutritional"]) && $_GET["Nutritional"] != 'ALL' ) 
	$sparql .=  " FILTER(regex(str(?NutritionalValue), '".$_GET["Nutritional"]."', 'i')) ";
	
 $sparql .=	 " } GROUP BY ?RecipeName ?ChefName ?Allerigic  ?Ingredients ?Duration ?Rating ?RecipeProcedure ?DifficultyLevel ?NutritionalValue ";

//echo $sparql;
$result = sparql_query( $sparql ); 
if( !$result ) { print sparql_errno() . ": " . sparql_error(). "\n"; exit; }
 
$fields = sparql_field_array( $result );
?>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link href="w3.css" rel="stylesheet"/>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<style>
	.bs-example{
    	margin: 20px;
    }
</style>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
</head>
<body>
<h3 class='h3 mb-3 mt-4 font-weight-normal text-center'>Recipe Finder</h3>

<div class="bs-example">
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a href="#home" class="nav-link active" data-toggle="tab">Home</a>
        </li>
        <li class="nav-item">
            <a href="#profile" class="nav-link" data-toggle="tab">Search</a>
        </li>
        <li class="nav-item">
            <a href="#messages" class="nav-link" data-toggle="tab">Filter</a>
        </li>
    </ul>
	<div class="tab-content">
        <div class="tab-pane fade show active  " id="home">
            <h5 class="mt-2"></h5>
            <p><font size="5">Welcome to <b>Recipe Finder</b> A place to find all your favorite recipes. From recipe recommendations just for you to nutritional information and helpful tips, Recipe Finder has everything you need to improve life in the kitchen.</font></p>
			<h5>Find the Perfect Recipe</h5>
			<p>A place to find all your favorite recipes. From recipe recommendations just for you to nutritional information and helpful tips, Recipe Finder has everything you need to improve life in the kitchen.</p>
			<h5>Sort & Filter by Ingredients</h5>
			<p>Recipe Finder's unique search filters allow you to narrow down your search by cook time, course, cuisine, occasion, diet, allergy, nutrition and more!</p>
			<h5>Diet & Allergy</h5>
			<p>Tell Recipe Finder your diet needs and allergies, and you’ll only see recipes that work for you.</p>
			<h5>Browse Your Favorites by difficulty level and ratings</h5>
			<p>Not sure where to start? Browse the recipes through difficulty level and individual ratings</p>
			<h5>Cook With The World's Best Chefs</h5>
			<p>Immerse yourself in a one-of-a-kind cooking experience featuring hands-on lessons from your favorite chefs. With recipes designed for beginners to advanced cooks, you'll learn from the best, one recipe at a time.</p>	
        </div>
	

        <div class="tab-pane fade" id="profile">
            <h4 class="mt-2">What would you like to cook today?</h4>
            <p>Search for a wide variety of recipes.</p>
			<div class='col-6 offset-3 text-center'><form method="GET"><input type="text" name="x" placeholder="Recipe Name" /><input type="submit" value="Search" /></form></div>
				<h4 class='h4 mb-3 mt-4 font-weight-normal text-center'>Number of recipes: <?php echo sparql_num_rows( $result ); ?></h4>
				<div class='col-4 '><table class='table table-bordered table-hover'></div>
				<thead class='thead-light'><tr></tr>

        
	<?php
		foreach( $fields as $field )
		{
			print "<th>$field</th>";
		}
		print "</tr></thead><tbody>";
		while( $row = sparql_fetch_array( $result ) )
		{
			print "<tr>";
			foreach( $fields as $field )
			{
				print "<td>".str_replace($fa, "", $row[$field] )."</td>";
			}
			print "</tr>";
		}
		print "";
	?>
</tbody></table></div>
</div>

<div class="tab-pane fade" id="messages">
            <h4 class="mt-2">Search by Filter</h4>
            <p>Filter your preferences to get the desired recipe</p>
			<div class='col-6 offset-3 text-center'><form method="GET">
<?php 

$sparql1 = "SELECT  ?Allerigic  WHERE {    ?recipe foaf:allerigic_to ?Allerigic .  }  GROUP BY ?Allerigic ";

//echo $sparql;
$result1 = sparql_query( $sparql1 ); 
if( !$result1 ) { print sparql_errno() . ": " . sparql_error(). "\n"; exit; }
 
$fields1 = sparql_field_array( $result1 );
?>
<b>Select Allergies:</b>
<select name="Allergies" id="a">
</div>
<?php 
foreach( $fields1 as $field1 )
{
	
}
print "<option selected='selected' value='ALL'>All</option>";
while( $row1 = sparql_fetch_array( $result1 ) )
{
	
	foreach( $fields1 as $field1 )
	{
		print "<option  value='".str_replace($fa, "", $row1[$field1] )."'>".str_replace($fa, "", $row1[$field1] )."</option>";
	}
	
}

?>
</select>


<br> 
<br>

<?php 

$sparql1 = "SELECT  ?ChefName
WHERE { 
   ?recipe foaf:has_chef_name ?ChefName . } GROUP BY ?ChefName ";

//echo $sparql;
$result1 = sparql_query( $sparql1 ); 
if( !$result1 ) { print sparql_errno() . ": " . sparql_error(). "\n"; exit; }
 
$fields1 = sparql_field_array( $result1 );
?>
<b>Select Chef Name:</b>
<select name="Chef" id="c">
<?php 
foreach( $fields1 as $field1 )
{
	
}
print "<option selected='selected' value='ALL'>All</option>";
while( $row1 = sparql_fetch_array( $result1 ) )
{
	
	foreach( $fields1 as $field1 )
	{
		print "<option  value='".str_replace($fa, "", $row1[$field1] )."'>".str_replace($fa, "", $row1[$field1] )."</option>";
	}
	
}

?>
</select>

<br>
<br>

<?php 

$sparql1 = "SELECT  ?Ingredients
WHERE { 
   ?recipe foaf:has_ingredient ?Ingredients . } GROUP BY ?Ingredients ";

//echo $sparql;
$result1 = sparql_query( $sparql1 ); 
if( !$result1 ) { print sparql_errno() . ": " . sparql_error(). "\n"; exit; }
 
$fields1 = sparql_field_array( $result1 );
?>
<b>Select Ingredients:</b>
<select name="Ingredient" id="i" multiple>
<?php 
foreach( $fields1 as $field1 )
{
	
}
print "<option selected='selected' value='ALL'>All</option>";
while( $row1 = sparql_fetch_array( $result1 ) )
{
	
	foreach( $fields1 as $field1 )
	{
		print "<option  value='".str_replace($fa, "", $row1[$field1] )."'>".str_replace($fa, "", $row1[$field1] )."</option>";
	}
	
}

?>
</select>


<br>
<br>

<?php 

$sparql1 = "SELECT  ?Duration
WHERE { 
   ?recipe foaf:has_duration ?Duration . } GROUP BY ?Duration ";

//echo $sparql;
$result1 = sparql_query( $sparql1 ); 
if( !$result1 ) { print sparql_errno() . ": " . sparql_error(). "\n"; exit; }
 
$fields1 = sparql_field_array( $result1 );
?>
<b>Select Duration:</b>
<select name="Duration" id="d">
<?php 
foreach( $fields1 as $field1 )
{
	
}
print "<option selected='selected' value='ALL'>All</option>";
while( $row1 = sparql_fetch_array( $result1 ) )
{
	
	foreach( $fields1 as $field1 )
	{
		print "<option  value='".str_replace($fa, "", $row1[$field1] )."'>".str_replace($fa, "", $row1[$field1] )."</option>";
	}
	
}

?>
</select>


<br>
<br>

<?php 

$sparql1 = "SELECT  ?Rating
WHERE { 
   ?recipe foaf:has_ratings ?Rating . } GROUP BY ?Rating ";

//echo $sparql;
$result1 = sparql_query( $sparql1 ); 
if( !$result1 ) { print sparql_errno() . ": " . sparql_error(). "\n"; exit; }
 
$fields1 = sparql_field_array( $result1 );
?>
<b>Select Rating:</b>
<select name="Rating" id="r">
<?php 
foreach( $fields1 as $field1 )
{
	
}
print "<option selected='selected' value='ALL'>All</option>";
while( $row1 = sparql_fetch_array( $result1 ) )
{
	
	foreach( $fields1 as $field1 )
	{
		print "<option  value='".str_replace($fa, "", $row1[$field1] )."'>".str_replace($fa, "", $row1[$field1] )."</option>";
	}
	
}

?>
</select>

<br>
<br>

<?php 

$sparql1 = "SELECT  ?DifficultyLevel
WHERE { 
   ?recipe foaf:has_difficulty_level ?DifficultyLevel . } GROUP BY ?DifficultyLevel ";

//echo $sparql;
$result1 = sparql_query( $sparql1 ); 
if( !$result1 ) { print sparql_errno() . ": " . sparql_error(). "\n"; exit; }
 
$fields1 = sparql_field_array( $result1 );
?>
<b>Select Difficulty Level:</b>
<select name="DDifficulty" id="d">
<?php 
foreach( $fields1 as $field1 )
{
	
}
print "<option selected='selected' value='ALL'>All</option>";
while( $row1 = sparql_fetch_array( $result1 ) )
{
	
	foreach( $fields1 as $field1 )
	{
		print "<option  value='".str_replace($fa, "", $row1[$field1] )."'>".str_replace($fa, "", $row1[$field1] )."</option>";
	}
	
}

?>
</select>

<br>
<br>

<?php 

$sparql1 = "SELECT  ?Cuisine
WHERE { 
   ?recipe foaf:under_cuisine ?Cuisine . } GROUP BY ?Cuisine ";

//echo $sparql;
$result1 = sparql_query( $sparql1 ); 
if( !$result1 ) { print sparql_errno() . ": " . sparql_error(). "\n"; exit; }
 
$fields1 = sparql_field_array( $result1 );
?>
<b>Select Cuisine:</b>
<select name="Cuisine" id="c">
<?php 
foreach( $fields1 as $field1 )
{
	
}
print "<option selected='selected' value='ALL'>All</option>";
while( $row1 = sparql_fetch_array( $result1 ) )
{
	
	foreach( $fields1 as $field1 )
	{
		print "<option  value='".str_replace($fa, "", $row1[$field1] )."'>".str_replace($fa, "", $row1[$field1] )."</option>";
	}
	
}

?>
</select>

<br>
<br>

<?php 

$sparql1 = "SELECT  ?NutritionalValue
WHERE { 
   ?recipe foaf:nutri_value ?NutritionalValue . } GROUP BY ?NutritionalValue ";

//echo $sparql;
$result1 = sparql_query( $sparql1 ); 
if( !$result1 ) { print sparql_errno() . ": " . sparql_error(). "\n"; exit; }
 
$fields1 = sparql_field_array( $result1 );
?>
<b>Select Nutritional Value:</b>
<select name="Nutritional" id="n" placeholder="Nutri">
<?php 
foreach( $fields1 as $field1 )
{
	
}
print "<option selected='selected' value='ALL'>All</option>";
while( $row1 = sparql_fetch_array( $result1 ) )
{
	
	foreach( $fields1 as $field1 )
	{
		print "<option  value='".str_replace($fa, "", $row1[$field1] )."'>".str_replace($fa, "", $row1[$field1] )."</option>";
	}
	
}

?>
</select>

<br>
<br>
<input type="submit" value="Search" /></form></div>
</div>
</body>
</html>
 