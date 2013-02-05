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
			<form name="deleteform" method="POST" action="/delete" onsubmit="return confirm('Are you Sure?');">
	        <input type="submit" href="delete" class="btn btn-danger" style="margin: 2px 10px; float: right" value="Remove my Data" />
			</form>
	        <ul class="nav nav-pills pull-right">	
		      <li <?php if($this->data["activeLink"] == NavLinks::DATES): ?>class="active"<?php endif; ?>><a href="/dates">Who wants to date you?</a></li>
	          <li <?php if($this->data["activeLink"] == NavLinks::APP): ?>class="active"<?php endif; ?>><a href="/application">App</a></li>
	          <li <?php if($this->data["activeLink"] == NavLinks::HOME): ?>class="active"<?php endif; ?>><a href="/">About</a></li>
	        </ul>
        <h3 class="muted"><span style="color:red">Lov</span>anonymous</h3>
      </div>

      <hr>