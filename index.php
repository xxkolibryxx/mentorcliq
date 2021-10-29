<?php

require_once 'upload.php';

$da = new DataAnalytics();
$results = array();
$high = 0;

if( isset($_FILES["data"]) )
{
    if( $da->uploadFile($_FILES["data"]) )
    {
        $da->dataAnalyze();
        $results = $da->getResults();
        $high = $da->getHighestAverage();

    }
}



?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.9/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.9/dist/sweetalert2.all.min.js"></script>
    <style>
        .users tr td:nth-child(3){
            text-transform: uppercase;
        } 
    </style>
    <title>Task</title>
</head>
<body>
    <div class="container">

    <?php if(!$da->isUploaded()): ?>
        <h3 class="text-center pt-4 pb-4">Upload .csv file</h3>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="input-group mb-3">
                <input type="file" name="data" class="form-control">
                <label class="input-group-text">Upload</label>
            </div>
            <div class="input-group mb-3">
                <label class="input-group-text">Division Percent</label>
                <input type="number" name="division_r" value="30" class="form-control" min="0" max="100">
            </div>
            <div class="input-group mb-3">
                
                <label class="input-group-text">Age Percent</label>
                <input type="number" name="age_r" value="30" class="form-control" min="0" max="100">
            </div>
            <div class="input-group mb-3">
                <label class="input-group-text">Timezon Percent</label>
                <input type="number" name="timezone_r" value="40" class="form-control" min="0" max="100">
            </div>
            <div class="input-group mb-3">
                <input type="submit" name="submit" class="btn btn-success">
            </div>
        </form>
        <?php if (isset($_POST['submit']) && !$da->isUploaded()):?>
        <script>
                    Swal.fire({
                        position: 'top-end',
                        icon: 'error',
                        title: 'File upload error!',
                        text: 'Empty file or file type is invalid',
                        showConfirmButton: false,
                        timer: 2500
                        })
                        </script>
        <?php endif ?>
    <?php else:?>
        <h3 class="text-center pt-4 pb-4">Users Board</h3>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Division</th>
                        <th>Age</th>
                        <th>UTC offset</th>
                    </tr>
                </thead>
                <tbody class="users">
                    <?php foreach( $results as $result ):?>
                        <tr>
                            <td>
                                <?=$result['name']?>
                            </td>
                            <td>
                                <?=$result['email']?>
                            </td>
                            <td>
                                <?=$result['division']?>
                            </td>
                            <td>
                                <?=$result['age']?>
                            </td>
                            <td>
                                <?=$result['timezone']?>
                            </td>
                        </tr>
                        
                    <?php endforeach;?>
                </tbody>
            </table>
        </div>
        <h3 class="text-center pt-4 pb-4">Score Board</h3>
        <h4 class="text-center pb-2">Highest Average for all scores: <?=$high?>%</h4>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Results</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach( $results as $result ):?>
                        <tr>
                            <td>
                                <?=$result['name']?>
                            </td>
                            <td>
                                <?php foreach( $result['compares'] as $compare ):?>
                                <?php if ($compare['score']==0){continue;} ?>
                                <table class="table table-striped">
                                    <tr>
                                        <td><?=$compare['name']?></td>
                                        <td class="text-end"><?=$compare['score']?>%</td>
                                    </tr>
                                </table>
                                <?php endforeach;?>
                            </td>
                        </tr>
                    <?php endforeach;?>
                </tbody>
            </table>
        </div>
    <?php endif;?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>