<?php
$cssAnsScriptFilesModule = array(
    '/plugins/jquery-simplePagination/jquery.simplePagination.js',
	'/plugins/jquery-simplePagination/simplePagination.css'
);
HtmlHelper::registerCssAndScriptsFiles($cssAnsScriptFilesModule, Yii::app()->getRequest()->getBaseUrl(true));
$cssJS = array(
    '/js/dataHelpers.js'
);
HtmlHelper::registerCssAndScriptsFiles($cssJS, Yii::app()->getModule( Yii::app()->params["module"]["parent"] )->getAssetsUrl() );

$layoutPath = 'webroot.themes.'.Yii::app()->theme->name.'.views.layouts.';
$me = isset(Yii::app()->session['userId']) ? Person::getById(Yii::app()->session['userId']) : null;
$this->renderPartial( $layoutPath.'modals.'.Yii::app()->params["CO2DomainName"].'.mainMenu', array("me"=>$me) );
?>

<style type="text/css">
	.round{
		border-radius: 100%;
		width: 250px;
		height: 250px;
		padding-top: 70px;
		border-color: #333;
 	}
</style>
<?php 
	if(	Yii::app()->session["userId"] == $form["author"] ||
		(!empty($form["links"]["forms"][Yii::app()->session["userId"]]) && 
			!empty($form["links"]["forms"][Yii::app()->session["userId"]]["isAdmin"]) &&
			$form["links"]["forms"][Yii::app()->session["userId"]]["isAdmin"] == true)){ ?>
<div class="panel panel-white col-lg-offset-1 col-lg-10 col-xs-12 no-padding">
	<div class="col-md-12 col-sm-12 col-xs-12 no-padding" id="goBackToHome">
		<a href="<?php echo Yii::app()->getRequest()->getBaseUrl(true) ?>/survey/co/admin/id/<?php echo $_GET['id']; ?>" class="col-md-12 col-sm-12 col-xs-12 padding-20 text-center bg-orange" id="btn-home" style="font-size:20px;"><i class="fa fa-home"></i> Back to administrator home</a>
	</div>
	<div class="col-md-12 col-sm-12 col-xs-12 text-center">
		<h1><?php echo "Membre du ".$form["title"] ?> <a href="<?php echo Yii::app()->getRequest()->getBaseUrl(true) ?>/survey/co/index/id/<?php echo $form["id"] ?>"><i class="fa fa-arrow-circle-right"></i></a> </h1>
		<div id="" class="" style="width:80%;  display: -webkit-inline-box;">
	    	<input type="text" class="form-control" id="input-search-table" 
					placeholder="search by name or by #tag, ex: 'commun' or '#commun'">
		    <button class="btn btn-default hidden-xs menu-btn-start-search-admin btn-directory-type">
		        <i class="fa fa-search"></i>
		    </button>
	    </div>
    </div>
	<div class="pageTable col-md-12 col-sm-12 col-xs-12 padding-20 text-center"></div>
	<div class="panel-body">
		<div>
			<a href="<?php echo '#element.invite.type.'.Form::COLLECTION.'.id.'.(string)$form['_id'] ; ?>" class="btn btn-primary btn-xs pull-right margin-10 lbhp">Invite Admins & Participants</a>
			<table class="table table-striped table-bordered table-hover  directoryTable" id="panelAdmin">
				<thead>
					<tr>
						<th>Name</th>
						<th>Email</th>
						<th>userID</th>
						<th>Read Answers</th>
						<th>BTN</th>
					</tr>
				</thead>
				<tbody class="directoryLines">
				</tbody>
			</table>
			
		</div>
	</div>
	<div class="pageTable col-md-12 col-sm-12 col-xs-12 padding-20"></div>
</div>

<script type="text/javascript">

	var form =<?php echo json_encode($form); ?>;
	var data =<?php echo json_encode($results); ?>;
		console.log("data", data);
	var searchAdmin={
		parentSurvey : form.id,
		text:null,
		page:"",
		//type:initType[0]
	};

	jQuery(document).ready(function() {
		bindLBHLinks();
		//bindAnwserList();
		if(typeof data != "undefined"){
			initViewTable(data);
		}


		$("#input-search-table").keyup(function(e){
			mylog.log("here", e.keyCode);
			//if(e.keyCode == 13){
			searchAdmin.page=0;
			searchAdmin.text = $(this).val();
			if(searchAdmin.text=="")
				searchAdmin.text=null;
			startAdminSearch(true);
			// Init of search for count
			if(searchAdmin.text===true)
				searchAdmin.text=null;
			//}
	    });
	});


	function startAdminSearch(initPage){
		//$("#second-search-bar").val(search);
	    $('#panelAdmin .directoryLines').html("Recherche en cours. Merci de patienter quelques instants...");
	    var data = {
	    	ifForm : form._id.$id,
	    	text : $("#input-search-table").val(),
	    }

	    $.ajax({ 
	        type: "POST",
	        url: baseUrl+'/'+activeModuleId+"/co/searchadminmembers/",
	        //url: baseUrl+"/"+moduleId+"/admin/directory/tpl/json",
	        data: searchAdmin,
	        dataType: "json",
	        success:function(data) { 
		          initViewTable(data);
		          //bindAnwserList();
		          // if(typeof data.results.count !="undefined")
		          // 	refreshCountBadge(data.results.count);
		          // console.log(data.results);
		          // if(initPage)
		          // 	initPageTable(data.results.count[searchAdmin.type]);
	        },
	        error:function(xhr, status, error){
	            $("#searchResults").html("erreur");
	        },
	        statusCode:{
	                404: function(){
	                    $("#searchResults").html("not found");
	            }
	        }
	    });
	}

	function initViewTable(data){
		console.log("initViewTable", data);
		$('#panelAdmin .directoryLines').html("");
		console.log("valueInit",data);
		$.each(data, function(key, value){

			entry=buildDirectoryLine(key, value);
			console.log("entry", entry);
			$("#panelAdmin .directoryLines").append(entry);
		});
		//bindAnwserList();
	}

	function buildDirectoryLine(key, value){
		console.log("buildDirectoryLine", key, value);
		str = '<tr>';
			str += '<td>'+value.name+'</td>';
			str += '<td>'+value.email+'</td>';
			str += '<td>'+key+'</td>';
			str += '<td>';
			if(typeof value.user != "undefined"){
				str += '<a href="'+baseUrl+'/survey/co/answer/id/'+form.id+'/user/'+value.user+'" >Read</a>';
			}
			str += '</td>';
			str += '<td id="active'+value.id+value.type+'">';
			if(typeof value.type != "undefined" && "projects" == value.type){
				//console.log("here", value.id, form.links.projectExtern[value.id]);
				if( typeof form.links == "undefined" || 
					typeof form.links.projectExtern == "undefined" || 
					typeof form.links.projectExtern[value.id] == "undefined") {
					str += '<a href="javascript:;" class="btn btn-primary activeBtn" data-id="'+value.id+'" data-type="'+value.type+'" data-name="'+value.name+'" ';
						if(typeof value.parentId != "undefined"  && typeof value.parentType != "undefined" ){
							str += ' data-parentid="'+value.parentId+'" data-parenttype="'+value.parentType+'" data-parentname="'+value.parentName+'"';
						}
					str += '>Valider</a>';
				}else {
					str += 'Déjà valider' ;
				}
			}
			str += '</td>';
		str += '</tr>';
		return str;
	}

	// function bindAnwserList(){

	// 	$(".activeBtn").on("click",function(e){
	// 		var params = {
	// 			childId : $(this).data("id"),
	// 			childType : $(this).data("type"),
	// 			childName : $(this).data("name"),
	// 			formId : form._id.$id,
	// 		};

	// 		if(typeof $(this).data("parentid") != "undefined" && typeof $(this).data("parenttype") != "undefined"){
	// 			params["parentId"] = $(this).data("parentid");
	// 			params["parentType"] = $(this).data("parenttype");
	// 			params["parentName"] = $(this).data("parentname");
	// 		}

	// 		$.ajax({
	// 			type: "POST",
	// 			url: baseUrl+'/'+activeModuleId+"/co/active/",
	// 			data:params,
	// 			dataType: "json",
	// 			success: function(view){
	// 				mylog.log("activeBtn ok", "#active"+params.childId+params.childType);
	// 				$("#active"+params.childId+params.childType).html("Projet valider");
	// 				toastr.success("Projet valider");
	// 			},
	// 			error: function (error) {
	// 				mylog.log("activeBtn error", error);
	// 				toastr.error("Projet non valider");
					
	// 			}
					
	// 		});
	// 	});
		
	// }
</script> 
<?php	
	} else {
		$this->renderPartial("unauthorised");
	} ?>

