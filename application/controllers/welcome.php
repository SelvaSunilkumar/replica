<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function connection() {
        /*$database_name = "tltmsdb";
        $database_password = "sunil";
        $database_username = "username";
        $database_host = "localhost";*/

        $connection = pg_connect("host=localhost port=5432 dbname=tltmsdb user=postgres password=sunil");
        //$connection = pg_connect("host=localhost port=5432 dbname=tltmsdb user=tltmsdbuser password=Tr@ck1ng");

        if ($connection) {
            return $connection;
        } else {
            echo "fail";
        }
    }

    public function updateLocation() {
        $connection = $this->connection();
        
        $device_id = $this->input->post("id");
        $latitude = $this->input->post("latitude");
        $longitude = $this->input->post("longitude");
        $datetime = $this->input->post("dataTimeStamp");
        $status = $this->input->post("status");

        /*$device_id = "sunil001";
        $latitude = 10.1234567;
        $longitude = 11.1234566;
        $datetime = "2020-03-06 11:27:01";
        $status = "Done";*/

        if ($connection) {
            $insert_query = "INSERT INTO user_location (phone_id, latitude, longitude, up_time, status) VALUES ('$device_id','$latitude','$longitude','$datetime','$status')";
            $insert_result = pg_query($connection,$insert_query);
            if ($insert_result) {
                echo "ok";
            } else {
                echo "fail";
            }
        } else {
            echo "fail";
        }
    }

    public function uploadImage() {

        $connection = $this->connection();
        date_default_timezone_set("Asia/Kolkata");

        $folder_name = date("d-m-y");
        //$folder_name = "21-02-21";

        $folder_path = "./uploadedImages";

        if (! file_exists($folder_path)) {
            if (!mkdir($folder_path, 0755, true));
        }

        $folder_path = "./uploadedImages/$folder_name";

        if (! file_exists($folder_path)) {
            if (!mkdir($folder_path, 0755, true));
        }

        $unique_Id = $_POST["systemId"];
        $dateAndTime = $_POST["exactTime"];
        $filename = str_replace(":","-",$dateAndTime);
        $filename = str_replace(" ","-",$filename);

        $folder_path = "./uploadedImages/".$folder_name."/".$unique_Id."-".$filename.".jpeg";

        $image = $_POST["image"];
        $latitude = $_POST["latitude"];
        $longitude = $_POST["longitude"];
        $location_status = $_POST["status"];

        if (file_put_contents($folder_path, base64_decode($image))) {
            //echo "ok";
			$url = "https://tltms.tce.edu/tracker/locationtracker/uploadedImages/$folder_name/$unique_Id-$filename.jpeg";

            $insert_image_query = "INSERT INTO user_images(phone_id, date_time, latitude, longitude, image_url, status) VALUES ('$unique_Id','$dateAndTime','$latitude','$longitude','$url','$location_status')";
            $insert_image_result = pg_query($connection,$insert_image_query);

            /*$insert_query = "INSERT INTO user_location (phone_id, latitude, longitude, up_time, status) VALUES ('$unique_Id','$latitude','$longitude','$dateAndTime','$location_status')";
            $insert_result = pg_query($connection,$insert_query);*/

            if ($insert_image_result) {
                echo "ok";
            } else {
                echo "fail";
            }

        } else {
            echo "fail";
        }
    }

    public function getLocation() {
        $connection = $this->connection();

        $get_location_query = "SELECT * FROM user_location";
        $get_location_result = pg_query($connection,$get_location_query);

        if ($get_location_result) {
            while ($get_location_attr = pg_fetch_array($get_location_result)) {
                echo $get_location_attr["latitude"]."&emsp;".$get_location_attr["longitude"]."&emsp;".$get_location_attr["up_time"]."&emsp;".$get_location_attr["status"]."&emsp;".$get_location_attr["phone_id"]."<br>";
            }
        } else {
            echo "fail";
        }
    }

    public function getImages() {
        $connection = $this->connection();

        $get_location_query = "SELECT * FROM user_images";
        $get_location_result = pg_query($connection,$get_location_query);

        if ($get_location_result) {
            while ($get_location_attr = pg_fetch_array($get_location_result)) {
                echo $get_location_attr["latitude"]."&emsp;".$get_location_attr["longitude"]."&emsp;".$get_location_attr["date_time"]."&emsp;".$get_location_attr["status"]."&emsp;".$get_location_attr["phone_id"]."<br>";
            }
        } else {
            echo "fail";
        }
    }

    public function addAccessPoint() {
        $connection = $this->connection();

        $latitude = $this->input->post('latitude');
        $longitude = $this->input->post('longitude');
        $locationName = $this->input->post('locationName');
        $distance = $this->input->post('distance');

        $upload_location_query = "INSERT INTO locations(latitude,longitude,locationname,distance) VALUES ('$latitude', '$longitude', '$locationName', '$distance ')";
        $upload_location_result = pg_query($connection, $upload_location_query);

        if ($upload_location_query) {
            echo "ok";
        } else {
            echo "error";
        }

    }

    public function getAccessPoints() {
        $connection = $this->connection();

        $get_location_query = "SELECT * FROM locations";
        $get_location_result = pg_query($connection,$get_location_query);

        if ($get_location_result) {
            while ($get_location_attr = pg_fetch_assoc($get_location_result)) {
                $location["data"][] = array(
                    'id' => $get_location_attr["indexer"],
                    'longitude' => $get_location_attr["longitude"],
                    'latitude' => $get_location_attr["latitude"],
                    'name' => $get_location_attr["locationname"],
                    'min' => $get_location_attr['distance']
                );
            }
            echo json_encode($location,true);
        }
    }

    public function create() {
        $connection = $this->connection();

        $create_query = 'CREATE TABLE userd (names TEXT, passwordd VARCHAR(50));';
        $create_result = pg_query($connection,$create_query);

        if ($create_query) {
            echo "pol";
        } else {
            echo pg_last_error($connection);
            echo "no";
        }
    }

    public function createLocator() {
        $connection = $this->connection();

        $create_query = 'CREATE TABLE locations (indexer SERIAL PRIMARY KEY, latitude DECIMAL(100,8), longitude DECIMAL(100,8), locationname TEXT, distance INT)';
        $create_result = pg_query($connection, $create_query);

        if ($create_result) {
            echo "ok";
        } else {
            echo "fail";
        }
    }

    //create table user_location (phone_id varchar(50), latitude decimal(10,8), longitude decimal(10,8), up_time timestamp, status text, location_id int serial primary key)

    //create table user_images (unique_id int serial primary key, phone_id varchar(50), date_time timestamp, latitude decimal(10,8), longitude decimal(10,8), image_url varchar(100), status text)

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */