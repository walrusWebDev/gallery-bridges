<?php

use Aws\S3\S3Client;

function gb_upload_image_to_s3( $file_path, $bucket_name, $object_key, $aws_access_key_id, $aws_secret_access_key, $aws_region = 'your-aws-region' ) {
    $s3Client = new S3Client([
        'region'      => $aws_region,
        'version'     => 'latest',
        'credentials' => [
            'key'    => $aws_access_key_id,
            'secret' => $aws_secret_access_key,
        ],
    ]);

    try {
        $result = $s3Client->putObject([
            'Bucket' => $bucket_name,
            'Key'    => $object_key,
            'Body'   => fopen($file_path, 'r'),
            'ACL'    => 'private', // Or 'public-read' if you want it publicly accessible
        ]);

        return $result['ObjectURL']; // Return the URL of the uploaded object
    } catch (\Aws\S3\Exception\S3Exception $e) {
        error_log('Error uploading to S3: ' . $e->getMessage());
        return false;
    }
}

// Example usage (you would adapt this to your plugin's workflow):
if (isset($_POST['upload_image'])) {
    $uploaded_file = $_FILES['image_file']['tmp_name'];
    $file_name = $_FILES['image_file']['name'];
    $bucket = 'your-domain-image-storage';
    $objectKey = 'collections/your-collection/' . $file_name; // Customize your object key

    $imageUrl = gb_upload_image_to_s3( $uploaded_file, $bucket, $objectKey, 'YOUR_AWS_ACCESS_KEY_ID', 'YOUR_AWS_SECRET_ACCESS_KEY', 'your-aws-region' );

    if ($imageUrl) {
        // Save $imageUrl to your database associated with the collection
        echo 'Image uploaded successfully: ' . esc_url($imageUrl);
    } else {
        echo 'Error uploading image.';
    }
}