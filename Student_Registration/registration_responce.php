<?php 
    session_start();
    if (isset($_SESSION['email'])) {
        include("../Include PHP/database_connection.php");
    
        $firstName = $_POST['first-name'];
        $lastName = $_POST['last-name'];
        $gender = $_POST['gender'];
        $batch = $_POST['bth'];
        $dept = $_POST['department'];
        $email = $_POST['email'];
        $phoneNumber = $_POST['pno'];

        $booleanCheckForID = FALSE;

        $sql = "SELECT regno from student;";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $regNo = $row['regno'];
            }
                $regNo += 1;
        } else {
                $regNo = 1;
        }

        $sql = "SELECT indexno from student
        WHERE department='{$dept}';";

        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $indexno = $row['indexno'];
            }
                $indexno += 1;
        } else {
                $indexno = 1;
        }     

        
        $name = $_FILES['sppimage']['name'];
        $target_dir = "../profile/";
        $target_file = $target_dir . basename($_FILES["sppimage"]["name"]);
        
        $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

        $extensions_arr = array("jpg","jpeg","png","gif");
    
        if( in_array($imageFileType,$extensions_arr) ){
             if(move_uploaded_file($_FILES['sppimage']['tmp_name'], $target_file)){

                $sql = "INSERT INTO STUDENT
                (FIRSTNAME,LASTNAME,REGNO,INDEXNO,GENDER,BATCH,DEPARTMENT,EMAIL,PHONENUMBER,IMAGE)
                VALUES
                ('{$firstName}','{$lastName}','{$regNo}','{$indexno}','{$gender}','{$batch}','{$dept}','{$email}','{$phoneNumber}','{$name}')";
                
                $conn->query($sql); 
                
                $booleanCheckForID = TRUE;

             } else {
                    echo "Registration Failed";
             }
        } else {
            echo "Registration Failed";
        }       
    } else {
        header("Location: index.php?error=Please Login with Admin Account to Access");
        exit();
    }

    if ($booleanCheckForID) {
        $sql = "SELECT * FROM STUDENT;";
        $result = $conn->query($sql);
        if ($result->num_rows>0) {
            while ($row=$result->fetch_assoc()) {
                $firstName = $row['FIRSTNAME'];
                $lastName = $row['LASTNAME'];
                $batch = $row['BATCH'];
                $dept = $row['DEPARTMENT'];
                $phoneNumber = $row['PHONENUMBER'];
                $regNo = $row['REGNO'];
                $indexno = $row['INDEXNO'];
                $image = $row['IMAGE'];
            }
            $regNo = "TE".sprintf("%04d", $regNo);
            $indexno = $dept.substr($batch,-2).sprintf("%03d", $indexno);
            $name = $firstName." ".$lastName;
            $image = "../profile/".$image;
        }

        require('fpdf.php');
        
	


}

        class PDF extends FPDF {
            function showImage() {
                    // Background
                    $this->Image('frameFOP.jpg',8,10,200);
            }
            function showProfile($image) {
                    $this->Image($image,130,30,55);
            }
            function showBarcode($barcodeImageURL) {
                // Display the barcode on the ID card
            $this->Image($barcodeImageURL,10,11,56,8);
                
            }
        }

        // Instanciation of inherited class
        $pdf = new PDF('L','mm','A5');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->SetFont('courier','B',16);
        $pdf->showImage();
        $pdf->showProfile($image);
        $pdf->cell(39,30,'');
        $pdf->ln();
        $pdf->cell(10,0,'');
        $pdf->cell(50,10,$name);
        $pdf->ln();
        $pdf->cell(10,10,'');
        $pdf->ln();
        $pdf->cell(10,10,'');
        $pdf->cell(39,10,$regNo);
        $pdf->cell(4,10,'');
        $pdf->cell(39,10,$dept);
        $pdf->ln();
        $pdf->cell(10,4,'');
        $pdf->ln();
        $pdf->cell(10,10,'');
        $pdf->cell(39,10,$phoneNumber);
        $pdf->cell(4,10,'');
        $pdf->cell(32,10,$indexno);
        $pdf->Output();


    
?>