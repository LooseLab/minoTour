<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <META HTTP-EQUIV="Pragma" CONTENT="no-cache">
    <META HTTP-EQUIV="Expires" CONTENT="-1">
    <title>minION Data Viewer - Alpha</title>

    <!-- Core CSS - Include with every page -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">

    <!-- Page-Level Plugin CSS - Dashboard -->
    <link href="css/plugins/morris/morris-0.4.3.min.css" rel="stylesheet">
    <link href="css/plugins/timeline/timeline.css" rel="stylesheet">

    <!-- SB Admin CSS - Include with every page -->
    <link href="css/sb-admin.css" rel="stylesheet">
    <link href="css/pnotify.custom.min.css" media="all" rel="stylesheet" type="text/css" />

</head>

<body>
 

    <div class="container">
		<div class="row">
		   							 <h4 class="text-center" style="color:white"><i class="fa fa-bolt"></i> minoTour - <em>real time data analysis for minION data</em> - minoTour <i class="fa fa-bolt"></i></h4>

		</div>
<?php if (gethostname() == "minotour.nottingham.ac.uk") { ?>
		<div class="row">
		<p class="text-center" style="color:white">Welcome to minoTour - to test out this site log in as user 'demo' with password 'demouser'. Datasets published by Joshua Quick, Aaron R Quinlan and Nicholas J Loman in <a href ="http://www.gigasciencejournal.com/content/3/1/22/abstract" target="_blank">GigaScience</a> are presented under the 'Previous Runs' (initially under 'Current Sequencing Runs') option in the left hand navigation.</p>
<p class="text-center" style="color:white">For access to minoTour or to set up your own servers please contact Matt Loose -> <a href="mailto:matt.loose@nottingham.ac.uk?Subject=minoTour%20information%20request" target="_top"><i style="color:white" class="fa fa-envelope-o"></i></a> <i class="fa fa-twitter-square" style="color:white"></i> @mattloose.</p>
		</div>
		<?php } ?>
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Please Sign In</h3>
                    </div>
                    <div class="panel-body">
                        <form role="form" method="post" action="index.php" name="loginform">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" id="login_input_username" placeholder="User Name" name="user_name" type="text" autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" id="login_input_password" placeholder="Password" name="user_password" autocomplete="off" type="password" required />
                                </div>
                                <!-- Change this to a button or input when using this as a form -->
								<input type="submit"  class="btn btn-lg btn-success btn-block" name="login" value="Log in" />
                            </fieldset>
                        </form>
						<h5><a href="register_new.php">Click Here To Register New account</a></h5>
                    </div>
                </div>
				
            </div>
			
        </div>
		
    </div>
	
	<?php
	// show potential errors / feedback (from login object)
	if (isset($login)) {
	    if ($login->errors) {
	        foreach ($login->errors as $error) {
	            echo $error;
	        }
	    }
	    if ($login->messages) {
	        foreach ($login->messages as $message) {
	            echo $message;
	        }
	    }
	}
	?>
	
		

	<div class="row">	
			<div class="center-block">
			
			<!-- 16:9 aspect ratio -->
			<div class="embed-responsive embed-responsive-16by9" style="text-align:center;">
			  <iframe class="embed-responsive-item" width="640" height="360" src="//www.youtube.com/embed/gbyvhJOrjZw" frameborder="0" allowfullscreen></iframe>
			</div>

		</div>
		
								 <h5 class="text-center" style="color:white"><i class="fa fa-bolt"></i> minoTour - <em>for more info on minoTour contact Matt Loose <a href="mailto:matt.loose@nottingham.ac.uk?Subject=minoTour%20information%20request" target="_top"><i style="color:white" class="fa fa-envelope-o"></i></a> <a href="http://www.twitter.com/mattloose" target="_blank"><i class="fa fa-twitter-square" style="color:white"></i></a></em> - minoTour <i style="color:white" class="fa fa-bolt"></i></h5>
								 <h5 class="text-center" style="color:white"><i class="fa fa-bolt" style="color:white"></i> Oxford Nanopore - <em>for more info on minoIONs see Oxford Nanopore <a href="https://www.nanoporetech.com" target="_blank"><i class="fa fa-globe" style="color:white"></i></a></em> - Oxford Nanopore <i style="color:white" class="fa fa-bolt"></i></h5>				 
	</div>
    <!-- Core Scripts - Include with every page -->
    <script src="js/jquery-1.10.2.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/plugins/metisMenu/jquery.metisMenu.js"></script>

    <!-- SB Admin Scripts - Include with every page -->
    <script src="js/sb-admin.js"></script>

<?php include "includes/reporting.php";?>
</body>

</html>
