<?PHP
    
    header('Content-Type: application/json');
    
    $data   =   array(
                         "name"          => "nsp-code/wp-hide",
                         "description"   => "Hide your WordPress and increase Security for your site using smart techniques. No files are changed on your server. Change default WordPress files URLs and login url.",
    
                        );
    
    echo json_encode($data);