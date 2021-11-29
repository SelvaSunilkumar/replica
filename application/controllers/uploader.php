<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Uploader extends CI_Controller {

    public function checkConnection() {
        
        include "dbconn.php";

        if ($connection) {
            echo "ok";
        } else {
            echo "fail";
        }

    }

    public function updateLocation() {

        include "dbconn.php";

        //$device_id = $this->input->post("");
        $device_id = $this->input->post("id");
        $latitude = $this->input->post("latitude");
        $longitude = $this->input->post("longitude");
        $datetime = $this->input->post("dataTimeStamp");
        $status = $this->input->post("status");

        /*$device_id = "sunil";
        $latitude = 0.0000;
        $longitude = 0.0000;
        $datetime = "2021-02-17 12:45:05";
        $status = "somewhere";*/

        $update_location_query = "INSERT INTO `user_location`(`phone_id`, `latitude`, `longitude`, `up_time`, `status`) VALUES ('$device_id','$latitude','$longitude','$datetime','$status')";
        $update_location_result = mysqli_query($connection,$update_location_query);

        if ($update_location_result) {
            echo "ok";
        } else {
            echo "fail";
        }

        //if insertion failed, then the data has to be stored in the device local database

    }

    public function uploadImage() {

        include "dbconn.php";
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

        $folder_path = "./uploadedImages/".$folder_name."/".$unique_Id."-".$dateAndTime.".jpeg";

        $image = $_POST["image"];
        $latitude = $_POST["latitude"];
        $longitude = $_POST["longitude"];
        $location_status = $_POST["status"];

        if (file_put_contents($folder_path, base64_decode($image))) {
            //echo "ok";
			$url = "http://localhost/locationtracker/uploadedImages/$folder_name/$unique_Id-$dateAndTime.jpeg";

            $insert_image_query = "INSERT INTO `user_images`(`phone_id`, `date_time`, `latitude`, `longitude`, `image_url`, `status`) VALUES ('$unique_Id','$dateAndTime','$latitude','$longitude','$url','$location_status')";
            $insert_image_result = mysqli_query($connection,$insert_image_query);

            if ($insert_image_result) {
                echo "ok";
            } else {
                echo "fail";
            }

        } else {
            echo "fail";
        }
    }

    public function getData() {
        include 'dbconn.php';

        $get_query = "SELECT * FROM user_location";
        $get_result = mysqli_query($connection,$get_query);

        if ($get_result) {
            while ($get_attr = mysqli_fetch_array($get_result)) {
                echo $get_attr["latitude"]." ".$get_attr["longitude"]." ".$get_attr["status"]."<br>";
            }
        } else {
            echo "fail";
        }
    }

}