<!DOCTYPE HTML>
<html lang="en">
<head>
	<title>Lovanonymous</title> 
	<link type="text/css" rel="stylesheet" href="static/bootstrap/css/bootstrap-responsive.css" />
	<link href="static/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen" />
	<script src="http://yui.yahooapis.com/3.2.0/build/yui/yui-min.js"></script>
	<script src="http://code.jquery.com/jquery-1.9.0.min.js" type="text/javascript"></script>
	<style type="text/css">
	      body {
	        padding-top: 20px;
	        padding-bottom: 40px;
	      }

	      /* Custom container */
	      .container-narrow {
	        margin: 0 auto;
	        max-width: 700px;
	      }
	      .container-narrow > hr {
	        margin: 30px 0;
	      }

	      /* Main marketing message and sign up button */
	      .jumbotron {
	        margin: 60px 0;
	        text-align: center;
	      }
	      .jumbotron h1 {
	        font-size: 72px;
	        line-height: 1;
	      }
	      .jumbotron .btn {
	        font-size: 21px;
	        padding: 14px 24px;
	      }

	      /* Supporting marketing content */
	      .marketing {
	        margin: 60px 0;
	      }
	      .marketing p + h4 {
	        margin-top: 28px;
	      }
			.friend {
				float: left;
				width: 270px;
				padding: 4px;
				margin: 5px;
				border: 2px solid #ddd;
				border-radius: 8px;
				cursor: pointer;
				height: 50px;
				background-color: #eee;
			}
			#friendlist {
				height: 400px;
				overflow: auto;
				border: 1px solid #ddd;
				margin: 0 0 10px;
				list-style: none;
			}
			.selected {
				background-color: #333;
				color: white;
			}
	    </style>	
					<style type="text/css" id="hiddencss" disabled>
						.hiddenfriend {
							display:none;
						}
					</style>
</head>
<body>
	<div class="container">
		
<div style="margin: 11px 0;"><iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fdyf.localhost.com%2F&amp;send=false&amp;layout=button_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=162431140571416" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:21px;" allowTransparency="true"></iframe></div>
      <div class="masthead">
			<form name="deleteform" method="POST" action="delete" onsubmit="return confirm('Are you Sure?');">
	        <input type="submit" href="delete" class="btn btn-danger" style="margin: 2px 10px; float: right" value="Remove my Data" />
			</form>
	        <ul class="nav nav-pills pull-right">	
		          <li><a href="possibledates">Who wants to date you?</a></li>
	          <li class="active"><a href="/">App</a></li>
	          <li><a href="about">About</a></li>
	          <li><a href="#">Contact</a></li>
	        </ul>
        <h3 class="muted"><span style="color:red">Lov</span>anonymous</h3>
      </div>

      <hr>
		<p >Select five of your friends</p>
		<input type="text" id="input" />
		<div id="result"></div>
			<form action ="/interests" method="POST">
			<ul id="friendlist">
			<?php foreach($this->data["friends"]["friends"] as $friend): ?>
				<li class="friend nothidden hiddenfriend" id="friend_<?php echo $friend["uid"] ?>"  frndid="<?php echo $friend["uid"] ?>">
					<input name="friend[]" type="checkbox" class="friendcheck" frndid="<?php echo $friend["uid"] ?>" id="friendcheck_<?php echo $friend["uid"] ?>" value="<?php echo $friend["uid"] ?>" />
					<div style="width:50px; height: 50px; display: inline;"><img src="https://graph.facebook.com/<?php echo $friend["uid"] ?>/picture" /></div> <?php echo $friend["name"]; ; ?>
				</li>
			<?php endforeach;?>
			</ul>
			<div style="text-align: right">
			<input type="Submit" value="Done" class='btn btn-primary' />
			</div>
			</form>
      <div class="footer">
        <p>&copy; Company 2012</p>
      </div>	

    </div> <!-- /container -->
	<script type="text/javascript" src="static/js/Trie.js"></script>
	<script type="text/javascript">
		function trim(s){ 
	  		return ( s || '' ).replace( /^\s+|\s+$/g, '' ); 
		}
		var _nameMap = <?php echo json_encode($this->data["friends"]["nameMap"]); ?>;
		var _names = <?php echo json_encode($this->data["friends"]["nameLst"]); ?>;
		var _midNames = Math.ceil(_names.length/2);
		var T = new Trie();
		var i;
		for(i = 0; i < _names.length; i++) {
		    T.insert(_names[i].toLowerCase());
		}
		      
		
		$(document).ready(function() {
			document.getElementById('hiddencss').disabled = true;
			$(".friend").click(function(e){
				var id = $(this).attr("frndid");
				var checkbox = "#friendcheck_" + id;
				var div = "#friend_" + id;
				if($(checkbox).is(":checked") && $(div).hasClass("selected")) {
					$(div).removeClass("selected");
					$(checkbox).removeAttr("checked");
				} else if(!$(checkbox).is(":checked") && !$(div).hasClass("selected")) {
					$(div).addClass("selected");
					$(checkbox).prop("checked", true);
				} else if($(checkbox).is(":checked")) {
					$(div).addClass("selected");
				} else {
					$(div).removeClass("selected");
					$(checkbox).removeAttr("checked");
				}
			});
			
			$("#input").keyup(function(e) {
			        e.stopPropagation();
		            var textInput,
		                suggestions,
		                html,
		                i;
		            textInput = trim($(this).val());
					$(".nothidden").addClass("hiddenfriend");
					$(".nothidden").removeClass("nothidden");
					if(textInput.length < 3) {
						document.getElementById('hiddencss').disabled = true;
						return;
					}
		            stateList = T.autoComplete(textInput.toLowerCase());
		
					document.getElementById('hiddencss').disabled = false;
		
		            for(i = 0; i < stateList.length; i++) {
						var _ids = _nameMap[stateList[i]];
						for(id in _ids) {
							$('#friend_' + _ids[id]).removeClass("hiddenfriend");
							$('#friend_' + _ids[id]).addClass("nothidden");
						}
		            }
		        });
		});
	</script>
</body>
</html>