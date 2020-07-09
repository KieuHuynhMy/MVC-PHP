<?
    class Product extends Connect {
        function index() {
            // Show product data
            $servername = 'localhost';
            $username = 'root';
            $password = '';
            $dbname = 'DTD';

            $conn = new mysqli($servername, $username, $password, $dbname);
            if($conn->connect_error) {
                die('Connection fail' . $connect_error);
            }
            
            $sql = "SELECT * FROM product";
            $result = $conn->query($sql);
            $i = 0;
            $showProduct = '';
            while($row = $result->fetch_assoc()) {
                $i++;
                $showProduct .= '
                    <tr id="tr'.$row['ID'].'">
                        <td>'.$i.'</td>
                        <td><img src="uploads/products/'.$row['image'].'" alt="hình" width="45"></td>
                        <td>
                            <span>'.$row['name'].'</span>
                        </td>
                        <td>
                            <a href="Product/edit/'.$row['ID'].'"><i class="fa fa-edit text-blue"></i></a>
                            <a href="#" data-toggle="modal" data-target="#delete'.$row['ID'].'"><i class="fa fa-trash text-red"></i></a>
                        </td>
                    </tr>

                    <div class="modal fade" id="delete'.$row['ID'].'">
                        <div class="modal-dialog">
                        <div class="modal-content bg-danger">
                            <div class="modal-header">
                                <h4 class="modal-title">Thông báo</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>Bạn có chắc chắn muốn xoá <b>'.$row['name'].'</b></p>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-outline-light" data-dismiss="modal">Thoát</button>
                                <button type="button" class="btn btn-outline-light" data-dismiss="modal" onclick="deleteProduct('.$row['ID'].')">Xoá ngay</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>';
            }

            // Search product
            if(isset($_GET['search'])) {
                $s = $_GET['search'];
                $sql = "SELECT * FROM product WHERE name LIKE '%$s%'";
                $result = $conn->query($sql);
                
                $i = 0;
                $showProduct = '';
                while($row = $result->fetch_assoc()) {
                    $i++;
                    $showProduct .= '
                        <tr id="tr'.$row['ID'].'">
                            <td>'.$i.'</td>
                            <td><img src="uploads/products/'.$row['image'].'" alt="hình" width="45"></td>
                            <td>
                                <span>'.$row['name'].'</span>
                            </td>
                            <td>
                                <a href="Product/edit/'.$row['ID'].'"><i class="fa fa-edit text-blue"></i></a>
                                <a href="#" data-toggle="modal" data-target="#delete'.$row['ID'].'"><i class="fa fa-trash text-red"></i></a>
                            </td>
                        </tr>

                        <div class="modal fade" id="delete'.$row['ID'].'">
                            <div class="modal-dialog">
                            <div class="modal-content bg-danger">
                                <div class="modal-header">
                                    <h4 class="modal-title">Thông báo</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p>Bạn có chắc chắn muốn xoá <b>'.$row['name'].'</b></p>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-outline-light" data-dismiss="modal">Thoát</button>
                                    <button type="button" class="btn btn-outline-light" data-dismiss="modal" onclick="deleteProduct('.$row['ID'].')">Xoá ngay</button>
                                </div>
                            </div>
                            <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>';
                }
            }

            // Show product from database in view
            $changeView['product'] = $showProduct;

            // Main view
            $changeView['main'] = 'product/main';
            $this->loadView('admin/index', $changeView);
        }

        // Delete product
        function delete($id = 0) {
            $servername = 'localhost';
            $username = 'root';
            $password = '';
            $dbname = 'DTD';

            $conn = new mysqli($servername, $username, $password, $dbname);
            if($conn->connect_error) {
                die('Connection fail' . $connect_error);
            }

            $sql = "DELETE FROM product WHERE ID = '".$id."'";
            
            if($conn->query($sql)) {
                echo 'Delete Successfully';
            } else {
                echo 'Error: ' . $sql . '<br>' . $connect_error;
            }
        }

        // Edit product view
        function edit($id = 0) {
            $servername = 'localhost';
            $username = 'root';
            $password = '';
            $dbname = 'DTD';

            $conn = new mysqli($servername, $username, $password, $dbname);
            if($conn->connect_error) {
                die('Connection fail' . $connect_error);
            }

            $sql = "SELECT * FROM product WHERE ID = ".$id."";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $changeView['edit'] = $row;
            
            $changeView['main'] = 'product/edit';
            $this->loadView('admin/index', $changeView);   
        }

        // Handle submit product
        function editProcess($id = 0) {
            // Check data in backend to send to database
            $name = $_POST['name'];
            $link = $_POST['link'];
            $content = $_POST['content'];
            $status = $_POST['status'];

            ( $status == 'on' ) ? $status = 1 : $status = 0;
            
            $flag = 1;
            $err = '';

            // Check product name
            if($name == '') {
                $flag = 0;
                $err .= "Tên sản phẩm không được rỗng \n";
            }

            if(strlen($name) > 70) {
                $flag = 0;
                $err .= "Tên sản phẩm không được lớn 70 ký tự \n";
            }

            $name_pattern = '/[\'^£$%&*()}{@#~?><>|=_¬:]/';
            if(preg_match($name_pattern, $name)) {
                $flag = 0;
                $err .= "Tên sản phẩm không được chứa ký tự đặc biệt \n";
            }

            // Check product link
            if($link == '') {
                $flag = 0;
                $err .= "Link sản phẩm không được rỗng \n";
            }

            if(strlen($link) > 70) {
                $flag = 0;
                $err .= "Link sản phẩm không được lớn 70 ký tự \n";
            }

            $link_pattern = '/[\'^£$%&*()}{@#~?><>,|=_¬]/';
            if(preg_match($link_pattern, $link)) {
                $flag = 0;
                $err .= "Link sản phẩm không được chứa ký tự đặc biệt \n";
            }

            // Check product image
            $servername = 'localhost';
            $username = 'root';
            $password = '';
            $dbname = 'DTD';

            $conn = new mysqli($servername, $username, $password, $dbname);
            if($conn->connect_error) {
                die('Connection fail' . $connect_error);
            }

            $sql_img = "SELECT * FROM product WHERE ID = '".$id."'";
            $result = $conn->query($sql_img);
            $row_img = $result->fetch_assoc();
            $image = $row_img['image'];

            if($_FILES['img']['name'] != '') {
                $target_dir = 'uploads/products/';
                $target_file = $target_dir . basename($_FILES["img"]["name"]);
                $flag_upload = 1;
                $err_upload = '';

                if(file_exists($target_file)) {
                    $flag_upload = 0;
                    $err_upload .= "Ảnh đã tồn tại \n";
                }

                if($_FILES['img']['size'] > 102400) {
                    $flag_upload = 0;
                    $err_upload .= "Ảnh không được vượt quá 100KB \n";
                }

                $img_pattern = '/image\/(png|jpg|jpeg)/';
                if(!preg_match($img_pattern, $_FILES['img']['type'])) {
                    $flag_upload = 0;
                    $err_upload .= "Ảnh không hợp lệ \n";
                }    

                if($flag_upload == 1) {
                    unlink('uploads/products/'.$row_img['image']); // Delete old picture in folder
                    move_uploaded_file($_FILES["img"]["tmp_name"], $target_file);
                    $image = $_FILES["img"]["name"];
                } else {
                    $flag = 0;
                    $err = $err_upload;
                }        
            } 

            if($flag == 1) {
                // Send data to database
                $servername = 'localhost';
                $username = 'root';
                $password = '';
                $dbname = 'DTD';

                $conn = new mysqli($servername, $username, $password, $dbname);
                if($conn->connect_error) {
                    die('Connection fail' . $connect_error);
                }

                // $sql = "UPDATE product SET name = '".$name."', link = '".$link."', image = '".$image."', content = '".$content."', status = '".$status."' WHERE ID = '".$id."'";
                $sql = "UPDATE product SET name = '".$name."', link = '".$link."', image = '".$image."', content = '".$content."', status = '".$status."' WHERE id = '".$id."'";
                
                if($conn->query($sql)) {
                    echo 'Update Successfully';
                } else {
                    echo 'Error: ' . $sql . '<br>' . $conn->error;
                }
            } else {
                echo $err;
            }
        }
        // Add product view
        function add() {
            $changeView['main'] = 'product/add';
            $this->loadView('admin/index', $changeView);           
        }

        // Handle submit product
        function addProcess() {
            // Check data in backend to send to database
            $name = $_POST['name'];
            $link = $_POST['link'];
            $content = $_POST['content'];
            $status = $_POST['status'];

            ( $status == 'on' ) ? $status = 1 : $status = 0;
            
            $flag = 1;
            $err = '';

            // Check product name
            if($name == '') {
                $flag = 0;
                $err .= "Tên sản phẩm không được rỗng \n";
            }

            if(strlen($name) > 70) {
                $flag = 0;
                $err .= "Tên sản phẩm không được lớn 70 ký tự \n";
            }

            $name_pattern = '/[\'^£$%&*()}{@#~?><>|=_¬:]/';
            if(preg_match($name_pattern, $name)) {
                $flag = 0;
                $err .= "Tên sản phẩm không được chứa ký tự đặc biệt \n";
            }

            // Check product link
            if($link == '') {
                $flag = 0;
                $err .= "Link sản phẩm không được rỗng \n";
            }

            if(strlen($link) > 70) {
                $flag = 0;
                $err .= "Link sản phẩm không được lớn 70 ký tự \n";
            }

            $link_pattern = '/[\'^£$%&*()}{@#~?><>,|=_¬]/';
            if(preg_match($link_pattern, $link)) {
                $flag = 0;
                $err .= "Link sản phẩm không được chứa ký tự đặc biệt \n";
            }

            // Check product image
            $image = '';
            if($_FILES['img']['name'] != '') {
                $target_dir = 'uploads/products/';
                $target_file = $target_dir . basename($_FILES["img"]["name"]);
                $flag_upload = 1;
                $err_upload = '';

                if(file_exists($target_file)) {
                    $flag_upload = 0;
                    $err_upload .= "Ảnh đã tồn tại \n";
                }

                if($_FILES['img']['size'] > 102400) {
                    $flag_upload = 0;
                    $err_upload .= "Ảnh không được vượt quá 100KB \n";
                }

                $img_pattern = '/image\/(png|jpg|jpeg)/';
                if(!preg_match($img_pattern, $_FILES['img']['type'])) {
                    $flag_upload = 0;
                    $err_upload .= "Ảnh không hợp lệ \n";
                }    

                if($flag_upload == 1) {
                    move_uploaded_file($_FILES["img"]["tmp_name"], $target_file);
                    $image = $_FILES["img"]["name"];
                } else {
                    $flag = 0;
                    $err .= $err_upload;
                }        
            } 

            if($flag == 1) {
                // Send data to database
                $servername = 'localhost';
                $username = 'root';
                $password = '';
                $dbname = 'DTD';

                $conn = new mysqli($servername, $username, $password, $dbname);
                if($conn->connect_error) {
                    die('Connection fail' . $connect_error);
                }

                $sql = "INSERT INTO product(name, link, image, content, status) VALUES ('".$name."', '".$link."', '".$image."', '".$content."', '".$status."')";
                
                if($conn->query($sql)) {
                    echo 'Insert Successfully';
                } else {
                    echo 'Error: ' . $sql . '<br>' . $conn->error;
                }
            } else {
                echo $err;
            }
        }
    }
?>