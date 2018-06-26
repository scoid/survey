<?php


//assets from ph base repo
$cssAnsScriptFilesTheme = array(
	// SHOWDOWN
	'/plugins/showdown/showdown.min.js',
	//MARKDOWN
	'/plugins/to-markdown/to-markdown.js'
);
HtmlHelper::registerCssAndScriptsFiles($cssAnsScriptFilesTheme, Yii::app()->request->baseUrl);

//gettting asstes from parent module repo
$cssAnsScriptFilesModule = array( 
	'/js/dataHelpers.js',
	//'/css/md.css',
);
HtmlHelper::registerCssAndScriptsFiles($cssAnsScriptFilesModule, Yii::app()->getModule( Yii::app()->params["module"]["parent"] )->getAssetsUrl() );

if( $this->layout != "//layouts/empty"){
	$layoutPath = 'webroot.themes.'.Yii::app()->theme->name.'.views.layouts.';
	$this->renderPartial($layoutPath.'header',array("page"=>"ressource","layoutPath"=>$layoutPath));
}
?>

<div class="panel panel-dark col-lg-offset-1 col-lg-10 col-xs-12 no-padding margin-top-50">
	<div class="col-xs-12 text-center">
		<h1><a href="/ph/survey/co/answers/id/<?php echo $form["id"]; ?>"><?php echo $form["title"]; ?></a> </h1>
		<h4 style="text-align: center;padding:10px;">Answers by <?php echo $user["name"]; ?> </h4>
    </div>

<div class="pageTable col-xs-12 padding-20 text-center"></div>
	<div class="panel-body">
		<div>	

	<?php 

	foreach ($form["scenario"] as $k => $v) {
		
		if(@$answers[$k]){?>
			<div class="bg-dark col-xs-12 text-center">
				<h1> <?php echo $v["form"]["title"]; ?></h1>
			</div>
			<?php 
				foreach ( $answers[$k]["answers"] as $key => $value) {
				
				echo "<div class='col-xs-12'>".
						"<h2> [ step ] ".$v["form"]["scenario"][$key]["title"]."</h2>";
				echo '<table class="table table-striped table-bordered table-hover  directoryTable" id="panelAdmin">'.
					'<thead>'.
						'<tr>'.
							'<th>Question</th>'.
							'<th>Answer</th>'.
						'</tr>'.
					'</thead>'.
					'<tbody class="directoryLines">';
				if(@$v["form"]["scenario"][$key]["json"])
				{
					$formQ = $v["form"]["scenario"][$key]["json"]["jsonSchema"]["properties"];
					foreach ($value as $q => $a) 
					{
						echo '<tr>';
							echo "<td>".$formQ[ $q ]["placeholder"]."</td>";
							echo "<td>".$a."</td>";
						echo '</tr>';
					}
				//todo search dynamically if key exists
				} 
				else if(@$v["form"]["scenario"]["survey"]["json"][$key])
				{
					$formQ = $v["form"]["scenario"]["survey"]["json"][$key]["jsonSchema"]["properties"];
					foreach ($value as $q => $a) {
						echo '<tr>';
							echo "<td>".$formQ[ $q ]["placeholder"]."</td>";
							echo "<td>".$a."</td>";
						echo '</tr>';
					}
				} 
				else if (@$v["form"]["scenario"][$key]["saveElement"]) 
				{
					$el = Element::getByTypeAndId( $value["type"] , $value["id"] );

					echo '<tr>';
						echo "<td> name </td>";
						echo "<td> ".$el["name"]."</td>";
					echo '</tr>';

					if(@$el["type"]){
						echo '<tr>';
							echo "<td> Type </td>";
							echo "<td>".$el["type"]."</td>";
						echo '</tr>';
					}

					if(@$el["description"]){
						echo '<tr>';
							echo "<td> Description </td>";
							echo "<td>".$el["description"]."</td>";
						echo '</tr>';
					}

					if(@$el["tags"]){
						echo '<tr>';
							echo "<td> Tags </td>";
							echo "<td>".$el["tags"]."</td>";
						echo '</tr>';
					}

					if(@$el["shortDescription"]){
						echo '<tr>';
							echo "<td> Short Description </td>";
							echo "<td>".$el["shortDescription"]."</td>";
						echo '</tr>';
					}

					if(@$el["email"]){
						echo '<tr>';
							echo "<td> Email </td>";
							echo "<td>".$el["email"]."</td>";
						echo '</tr>';
					}
					
					if(@$el["profilImageUrl"]){
						echo '<tr>';
							echo "<td> profilImageUrl </td>";
							echo "<td><img src='".Yii::app()->createUrl($el["profilImageUrl"])."'/></td>";
						echo '</tr>';
					}

					if(@$el["url"]){
						echo '<tr>';
							echo "<td> URL </td>";
							echo "<td><a href='".$el["url"]."'>Site</a></td>";
						echo '</tr>';
					}

					echo '<tr>';
						echo "<td> link </td>";
						echo "<td> <a target='_blank' class='btn btn-default' href='/ph/co2#page.type.".$value["type"].".id.".$value["id"]."'>".$value["type"]."</a></td>";
					echo '</tr>';
				}
				echo "</tbody></table></div>";
			}
		} else { ?>
		<div class="bg-red col-xs-12 text-center text-large"><h1> <?php echo $v["form"]["title"]; ?></h1></div>
		<?php 
			echo "<h3 style='color:red' class='text-center'> This step ".$k." hasn't been filed yet.</h3>";
		}
	}
	?>
</div>
</div>
</div>



<script type="text/javascript">

$(document).ready(function() { 
	$('#doc').html( dataHelper.markdownToHtml( $('#doc').html() ) );		
});

</script>