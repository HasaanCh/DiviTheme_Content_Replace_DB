<?php


    // importing files
    $path = preg_replace('/wp-content.*$/','',__DIR__);
    require_once('../assests/SimpleXLSX.php');
    require_once($path.'wp-load.php');
    include('database.php');
    include('matchingf.php');

    // if(function_exists('replaceText'))
    // {
    //     print_r("Database connected");
    // }




    if ( ! function_exists( 'wp_handle_upload' ) ) {
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
    }
    
    

    // Checking if the page is working properly or not
    echo "Hello there everything working fine";

    // Processing file
    if(isset($_POST['content_submitted']))
    {
      
        // File uploading set
        $target_dir_array =wp_upload_dir();
        $target_dir=$target_dir_array['path'];
        $target_file=$target_dir.basename($_FILES['excel_file']['name']);
        if(move_uploaded_file($_FILES['excel_file']['tmp_name'],$target_file))
        {
            if ( $xlsx = SimpleXLSX::parse($target_file) ) 
            {
             
                    // Getting data from Excel in Result Variable
                    // $result= $xlsx->rows();

                    // New Testing
                    // $counter=1;
                    $sheets=$xlsx->sheetNames();
                    $number_of_rows=count($sheets);
                    echo "<br>Total number of sheets are:";
                    print_r($number_of_rows);

                    foreach($sheets as $index => $name)
                    {
                        echo "<br>Index of current page is:";
                        print_r($index);
                        $aloo=$xlsx->rows($index);
                        // echo "<br> Hello new Iteration";

                        if($aloo[0][0] == "page id")
                        {
                            
                            $page_id=$aloo[0][1];
                            $database=dbdata($page_id);
                            $ss=$database[0]->post_content;
                            echo"<br>Page Id is:";
                            print_r($page_id);
                            foreach ( $xlsx->rows($index) as $r => $row ) 
                            {
                                
                                    $id=$row[1];
                                    $content_to=$row[2];

                                    if($row[0]=="page id" || $row[0]=="Current Text On the Site" )
                                    {
                                    //    echo "<br><br>";
                                    }
                                    
                                    else
                                    {
                                         echo '<br>Id being used is: ';
                                        print_r($id);
                                        if($content_to=="")
                                        {
                                            echo "<br><br>Content not found for id:";
                                            print_r($id);
                                            echo"<br><br>";
                                        }
                                        else
                                        {
                                            $ss=replaceText($ss,$id,$content_to);
                                        }
                                        
                                    }
                            }
                            insertdata($page_id,$ss);
                        }

                        else 
                        {
                            echo "<br>Page skipped<br>";
                        }
                        

                    }

              } else 
              {
                echo SimpleXLSX::parseError();
              }
        }

        else
        {
            echo "Error";        
        }

    }



?>