<!DOCTYPE HTML>
<html lang="en">
<head>
	<title>Lovanonymous</title> 
	<link rel="shortcut icon" href="/static/images/favicon.png" />
	<link href="/static/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen" />
	<link type="text/css" rel="stylesheet" href="/static/bootstrap/css/bootstrap-responsive.css" />
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
				width: 320px;
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
				background-color: #FBEFFB;
			}
			.selected {
				background-color: #333;
				color: white;
			}
			.muted {
				text-align:center;
			}
	    </style>	
					<style type="text/css" id="hiddencss" disabled>
						.hiddenfriend {
							display:none;
						}
					</style>
</head>
<body style="padding-top: 0px; margin-top: 0px; background-color:#FBEFFB;">
	<div class="navbar navbar-inverse navbar-fixed-top">
	      <div class="navbar-inner">
	        <div class="container">
	          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
	            <span class="icon-bar"></span>
	            <span class="icon-bar"></span>
	            <span class="icon-bar"></span>
	          </a>
	          <a class="brand <?php if($this->data["activeLink"] == NavLinks::HOME): ?>active<?php endif; ?>" href=<?php echo ($this->data["loggedIn"] ? "/application" : "/") ?>>Luv[anonymous]</a>
	          <div class="nav-collapse collapse">
	            <ul class="nav">
					  <?php if($this->data["loggedIn"]): ?>
				      <li <?php if($this->data["activeLink"] == NavLinks::DATES): ?>class="active"<?php endif; ?>><a href="/dates">Who wants to date you?</a></li>
				      <li <?php if($this->data["activeLink"] == NavLinks::HOME): ?>class="active"<?php endif; ?>><a href="/">About</a></li>
						  <?php endif;?>
				      <li <?php if($this->data["activeLink"] == NavLinks::PRIVACY): ?>class="active"<?php endif; ?>><a href="/privacy">Privacy</a></li>
			              	<?php if($this->data["loggedIn"]): ?>
							<li><a href="/application">Choose your Friend</a></li>
							<li><form name="deleteform" method="POST" action="/delete" onsubmit="return confirm('You are going to remove your data! Are you Sure?');">
					        <input type="submit" href="delete" class="btn btn-danger" style="margin: 5px 10px; float: right" value="Remove my Data" />
							</form></li>
							<?php endif;?>
	            </ul>
	          </div><!--/.nav-collapse -->
	
	<div class="pull-right" style="margin: 10px 0 0; width: 64px;"><iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fdyf.localhost.com%2F&amp;send=false&amp;layout=button_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=162431140571416" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:21px;" allowTransparency="true"></iframe></div>
	        </div>
	      </div>
	    </div>

      <div class="container">