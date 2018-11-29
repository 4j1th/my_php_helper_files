<?php

$uploadOk = 1;
$imageFileType = strtolower(pathinfo($_FILES["file"]["name"],PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
$check = getimagesize($_FILES["file"]["tmp_name"]);
if($check !== false) {
    // echo "File is an image - " . $check["mime"] . ".";
    $uploadOk = 1;
} else {
    $result['response'] =  "File is not an image.";
    $uploadOk = 0;
}

// Check if file already exists
/*if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}*/
// Check file size
if ($_FILES["file"]["size"] > 500000) {
    $result['response'] = "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
    
    //$result['response'] =  "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $result['response'] = 'incorrect image type';
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    
    // echo "Sorry, your file was not uploaded.";
    $result['response'] = 'upload failed';

// if everything is ok, try to upload file
} else {

    $bucket = $storage->bucket($this->config->item('gcs_coldline_singapore'));
    $bucket->upload(
        fopen($_FILES["file"]["tmp_name"], 'r'),
        [
            'name' => 'w_2000/products/'.$post['tempid'].'/'.$post['filenum'].'.'.$imageFileType,
            // 'predefinedAcl' => 'publicRead'
        ]
    );
    // chnage bucket to mumbai regional
    $bucket = $storage->bucket($this->config->item('gcs_regional_mumbai'));

    /*if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
        
        echo "The file ". basename( $_FILES["file"]["name"]). " has been uploaded.";

    } else {
        
        // echo "Sorry, there was an error uploading your file.";
        $result['response'] = 'upload failed';
    }*/

    $filename = $_FILES["file"]["tmp_name"];

    // add to db
    if ( !$file_exist['num'] )  {

        $this->seller_productdb->add_img_to_product($post, $post['filenum'].'.'.$imageFileType);
    }

    // for png
    if ($imageFileType == 'png') {

        $this->load->library('image_lib');

        $config['image_library'] = 'gd2';
        $config['source_image'] = $filename;
        $config['quality'] = '100';
        $config['create_thumb'] = FALSE;
        $config['maintain_ratio'] = TRUE;
        $config['width']         = 1400;
        $config['height']       = 800;
        $config['new_image'] = tempnam(sys_get_temp_dir(), 'convert_files');

        $this->image_lib->clear();
        $this->image_lib->initialize($config);
        $this->image_lib->resize();

        // upload to cloud
        $bucket->upload(
            fopen($config['new_image'], 'r'),
            [
                'name' => 'w_1400/products/'.$post['tempid'].'/'.$post['filenum'].'.'.$imageFileType,
                'predefinedAcl' => 'publicRead'
            ]
        );
        unlink($config['new_image']);

        $config['image_library'] = 'gd2';
        $config['source_image'] = $filename;
        $config['quality'] = '100';
        $config['create_thumb'] = FALSE;
        $config['maintain_ratio'] = TRUE;
        $config['width']         = 800;
        $config['height']       = 800;
        $config['new_image'] = tempnam(sys_get_temp_dir(), 'convert_files');

        $this->image_lib->clear();
        $this->image_lib->initialize($config);
        $this->image_lib->resize();

        // upload to cloud
        $bucket->upload(
            fopen($config['new_image'], 'r'),
            [
                'name' => 'w_800/products/'.$post['tempid'].'/'.$post['filenum'].'.'.$imageFileType,
                'predefinedAcl' => 'publicRead'
            ]
        );
        unlink($config['new_image']);

        $config['image_library'] = 'gd2';
        $config['source_image'] = $filename;
        $config['quality'] = '100';
        $config['create_thumb'] = FALSE;
        $config['maintain_ratio'] = TRUE;
        $config['width']         = 400;
        $config['height']       = 800;
        $config['new_image'] = tempnam(sys_get_temp_dir(), 'convert_files');

        $this->image_lib->clear();
        $this->image_lib->initialize($config);
        $this->image_lib->resize();

        // upload to cloud
        $bucket->upload(
            fopen($config['new_image'], 'r'),
            [
                'name' => 'w_400/products/'.$post['tempid'].'/'.$post['filenum'].'.'.$imageFileType,
                'predefinedAcl' => 'publicRead'
            ]
        );
        unlink($config['new_image']);
        
        $result['response'] = 'success';

    } else {

        // create different size copys

        //resize: 1300
        $width = 1400;
        $height = 800;
        // get source image size
        list($width_orig, $height_orig) = getimagesize($filename);
        $ratio_orig = $width_orig / $height_orig;
        // set image size
        if ($width / $height > $ratio_orig) {
            $width = $height * $ratio_orig;
        } else {
            $height = $width / $ratio_orig;
        }
        // Resample
        $image_p = imagecreatetruecolor($width, $height);
        $image = @imagecreatefromjpeg($filename);
        //echo $image;
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
        // Output
        $temp_loc = tempnam(sys_get_temp_dir(), 'convert_files');
        imagejpeg($image_p, $temp_loc, 100);
        // upload to cloud
        $bucket->upload(
            fopen($temp_loc, 'r'),
            [
                'name' => 'w_1400/products/'.$post['tempid'].'/'.$post['filenum'].'.'.$imageFileType,
                'predefinedAcl' => 'publicRead'
            ]
        );
        unlink($temp_loc);

        //resize: 500
        $width = 800;
        $height = 800;
        // get source image size
        //list($width_orig, $height_orig) = getimagesize($filename);
        $ratio_orig = $width_orig / $height_orig;
        // set image size
        if ($width / $height > $ratio_orig) {
            $width = $height * $ratio_orig;
        } else {
            $height = $width / $ratio_orig;
        }
        // Resample
        $image_p = imagecreatetruecolor($width, $height);
        $image = @imagecreatefromjpeg($filename);
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
        // Output
        $temp_loc = tempnam(sys_get_temp_dir(), 'convert_files');
        imagejpeg($image_p, $temp_loc, 100);
        // upload to cloud
        $bucket->upload(
            fopen($temp_loc, 'r'),
            [
                'name' => 'w_800/products/'.$post['tempid'].'/'.$post['filenum'].'.'.$imageFileType,
                'predefinedAcl' => 'publicRead'
            ]
        );
        unlink($temp_loc);

        //resize: 360
        $width = 400;
        $height = 800;
        // get source image size
        //list($width_orig, $height_orig) = getimagesize($filename);
        $ratio_orig = $width_orig / $height_orig;
        // set image size
        if ($width / $height > $ratio_orig) {
            $width = $height * $ratio_orig;
        } else {
            $height = $width / $ratio_orig;
        }
        // Resample
        $image_p = imagecreatetruecolor($width, $height);
        $image = @imagecreatefromjpeg($filename);
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
        // Output
        $temp_loc = tempnam(sys_get_temp_dir(), 'convert_files');
        imagejpeg($image_p, $temp_loc, 100);
        // upload to cloud
        $bucket->upload(
            fopen($temp_loc, 'r'),
            [
                'name' => 'w_400/products/'.$post['tempid'].'/'.$post['filenum'].'.'.$imageFileType,
                'predefinedAcl' => 'publicRead'
            ]
        );
        unlink($temp_loc);

        $result['response'] = 'success'; 
    }