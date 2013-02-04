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
			}
			.selected {
				background-color: #333;
				color: white;
			}
			.hidden {
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
			          <li class="active"><a href="possibledates">Who wants to date you?</a></li>
		          <li><a href="/">App</a></li>
		          <li><a href="about">About</a></li>
		          <li><a href="#">Contact</a></li>
		        </ul>
	        <h3 class="muted"><span style="color:red">Lov</span>anonymous</h3>
	      </div>

      <hr>
		<h2 class="jumbotron">Following People Want to Date you</h2>
		<ul id="friendlist">
		<?php foreach($matched as $friend): ?>
			<?php $friend = json_decode($friend, true); ?>
			<li class="friend nothidden hiddenfriend" id="friend_<?php echo $friend["uid"] ?>"  frndid="<?php echo $friend["uid"] ?>">
				<div style="width:50px; height: 50px; display: inline;"><img src="https://graph.facebook.com/<?php echo $friend["uid"] ?>/picture" /></div> <?php echo $friend["name"]; ; ?>
			</li>
		<?php endforeach;?>
		</ul>
      <div class="footer">
        <p>&copy; Company 2012</p>
      </div>

    </div> <!-- /container -->
</body>
</html>