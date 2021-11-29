<?php 

    //$connection = pg_connect("host=localhost port=5432 dbname=tltmsdb user=postgres password=sunil");
    $connection = pg_connect("host=localhost port=5432 dbname=tltmsdb user=tltmsdbuser password=Tr@ck1ng");

    $get_location_list_query = "SELECT * FROM locations";
    $get_location_list_result = pg_query($connection, $get_location_list_query);

    if (isset($_POST['add'])) {
        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];
        $locationName = $_POST['locationName'];
        $distance = $_POST['distance'];

        $upload_new_location_query = "INSERT INTO locations(latitude, longitude, locationname, distance) VALUES ('$latitude','$longitude','$locationName','$distance')";
        $upload_new_location_result = pg_query($connection, $upload_new_location_query);

        if ($upload_new_location_result) {
            $uploaded = true;
            $uploaded_status = "Location uploaded <b>Successfully</b>";
        } else {
            $uploaded = false;
            $uploaded_status = "Location Uploading <b>Failed</b>";
        }
    }

    if (isset($_GET['delete'])) {
        $id = $_GET['id'];
        echo $id;
        $delete_location_query = "DELETE FROM locations WHERE indexer='$id'";
        $delete_location_result = pg_query($connection, $delete_location_query);

        if ($delete_location_result) {
            $uploaded = true;
            $uploaded_status = "Location Deleted <b>Successfully</b>";
        } else {
            $uploaded = false;
            $uploaded_status = "Location Deletion <b>Failed</b>";
        }
    }
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Add Location</title>
		<link rel="shortcut icon" type="image/icon" href="https://img.collegedekhocdn.com/media/img/institute/logo/tce_logo.png">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script> 
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	</head>
	<body style="background: #ccc;">
		<div class="container" style="margin-top: 20px;">
			<div class="card shadow">
				<div class="card-header text-center shadow bg-transparent">
					<a href="http://localhost/resume/index.php/auth/aboutus">
						<img src="https://img.collegedekhocdn.com/media/img/institute/logo/tce_logo.png" height=100px;>
					</a>
				</div>
				
                <form method="POST" action="<?php echo base_url(); ?>index.php/locater/addLocation">
                    <input type="text" name="add" value="1" style="display: none;">
                    <div class="card-body">
                        <?php 
                        
                            if (isset($uploaded)) {
                                if ($uploaded) {
                                    ?>
                                    
                                    <div class="alert alert-success text-center">
                                        <?php 
                                            echo $uploaded_status;
                                        ?>
                                    </div>
                                    
                                    <?php
                                } else {
                                    ?> 
                                    
                                        <div class="alert alert-danger text-center">
                                            <?php 
                                                echo $uploaded_status;
                                            ?>
                                        </div>

                                    <?php
                                }
                            }
                        
                        ?>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="">Latitude</label>
                                    <input type="text" name="latitude" class="form-control form-control-sm" required>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="">Longitude</label>
                                    <input type="text" name="longitude" class="form-control form-control-sm" required>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="">Location Name</label>
                                    <input type="text" name="locationName" class="form-control form-control-sm" required>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="">Min Distance <small>(Accuracy)</small></label>
                                    <input type="number" name="distance" class="form-control form-control-sm" required>
                                </div>
                            </div>
                        </div>
				    </div>
                    <div class="card-footer">
                        <input type="submit" class="btn btn-success" value="Add Location">
                        <a href="<?php echo base_url(); ?>index.php/locater/addLocation" class="btn btn-primary" style="border-radius: 50%;"><i class="fa fa-refresh"></i></a>
                    </div>
                </form>
			</div>
		</div>
        <div class="container" style="margin-top: 20px;">
            <div class="card shadow">
				<table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Latitude</th>
                            <th>Longitude</th>
                            <th>Location Marker</th>
                            <th>Min distace <small>(Accuracy)</small></th>
                            <th><i class="fa fa-trash"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $iterator = 1;
                            if ($get_location_list_result) {

                                while ($loation_attr = pg_fetch_assoc($get_location_list_result)) {
                                    ?>
                                        <tr>
                                            <td><?php echo $iterator; ?></td>
                                            <td><?php echo $loation_attr['latitude']; ?></td>
                                            <td><?php echo $loation_attr['longitude']; ?></td>
                                            <td><?php echo $loation_attr['locationname']; ?></td>
                                            <td><?php echo $loation_attr['distance']; ?></td>
                                            <td><a href="?delete=1&id=<?php echo $loation_attr['indexer']; ?>">Delete</a></td>
                                        </tr>
                                    <?php
                                    $iterator = $iterator + 1;
                                }

                            } else {

                            }
                        
                        ?>
                    </tbody>
                </table>
			</div>
        </div>
    </body>
</html>