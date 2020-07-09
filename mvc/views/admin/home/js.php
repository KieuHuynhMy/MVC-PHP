<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="assets/admin/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="assets/admin/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- overlayScrollbars -->
<script src="assets/admin/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="assets/admin/dist/js/adminlte.js"></script>

<!-- OPTIONAL SCRIPTS -->
<script src="assets/admin/dist/js/demo.js"></script>

<!-- PAGE PLUGINS -->
<!-- jQuery Mapael -->
<script src="assets/admin/plugins/jquery-mousewheel/jquery.mousewheel.js"></script>
<script src="assets/admin/plugins/raphael/raphael.min.js"></script>
<script src="assets/admin/plugins/jquery-mapael/jquery.mapael.min.js"></script>
<script src="assets/admin/plugins/jquery-mapael/maps/usa_states.min.js"></script>
<!-- ChartJS -->
<script src="assets/admin/plugins/chart.js/Chart.min.js"></script>

<!-- PAGE SCRIPTS -->
<script src="assets/admin/dist/js/pages/dashboard2.js"></script>

<!-- bs-custom-file-input -->
<script src="assets/admin/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<script>
$(document).ready(function () {
    bsCustomFileInput.init();
});
</script>

<!-- Insert CKEDITOR -->
<script src="assets/admin/js/ckeditor/ckeditor.js"></script>
<script>
    CKEDITOR.replace('content');
</script>

<script>
    function ChangeToSlug()
    {
        var title, slug;
    
        //Lấy text từ thẻ input title 
        title = document.getElementById("name").value;
    
        //Đổi chữ hoa thành chữ thường
        slug = title.toLowerCase();
    
        //Đổi ký tự có dấu thành không dấu
        slug = slug.replace(/á|à|ả|ạ|ã|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ/gi, 'a');
        slug = slug.replace(/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/gi, 'e');
        slug = slug.replace(/i|í|ì|ỉ|ĩ|ị/gi, 'i');
        slug = slug.replace(/ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ/gi, 'o');
        slug = slug.replace(/ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự/gi, 'u');
        slug = slug.replace(/ý|ỳ|ỷ|ỹ|ỵ/gi, 'y');
        slug = slug.replace(/đ/gi, 'd');
        //Xóa các ký tự đặt biệt
        slug = slug.replace(/\`|\~|\!|\@|\#|\||\$|\%|\^|\&|\*|\(|\)|\+|\=|\,|\.|\/|\?|\>|\<|\'|\"|\:|\;|_/gi, '');
        //Đổi khoảng trắng thành ký tự gạch ngang
        slug = slug.replace(/ /gi, "-");
        //Đổi nhiều ký tự gạch ngang liên tiếp thành 1 ký tự gạch ngang
        //Phòng trường hợp người nhập vào quá nhiều ký tự trắng
        slug = slug.replace(/\-\-\-\-\-/gi, '-');
        slug = slug.replace(/\-\-\-\-/gi, '-');
        slug = slug.replace(/\-\-\-/gi, '-');
        slug = slug.replace(/\-\-/gi, '-');
        //Xóa các ký tự gạch ngang ở đầu và cuối
        slug = '@' + slug + '@';
        slug = slug.replace(/\@\-|\-\@|\@/gi, '');
        //In slug ra textbox có id “slug”
        document.getElementById('link').value = slug;
    }
</script>
<script>
    <?php $url = explode('/',$_GET['url']);
        if($url[1] != 'index') {
    ?>
        $(function() {
            $('#product-form').on('submit', function(e) {
                e.preventDefault();

                // Check data in frontend
                var name = $('#name').val();
                var link = $('#link').val();
                var image = $('#img').val();
                var content = $('#content').val();
                var status = $('#status').val();
                var flag = 1;
                var err = '';
                
                // Check product name
                if(name.length > 70) {
                    flag = 0;
                    err += 'Tên sản phẩm không được lớn 70 ký tự \n';
                }

                var name_pattern = /[!@#$%^&*()_\-=\[\]{};':"\\|.<>\/?]/;
                if(name.match(name_pattern)) {
                    flag = 0;
                    err += 'Tên sản phẩm không được chứa ký tự đặc biệt \n';
                }

                // Check product link
                if(link.length > 70) {
                    flag = 0;
                    err += 'Link sản phẩm không được lớn 70 ký tự \n';
                }

                var link_pattern = /[!@#$%^&*()_=\[\]{};':"\\|,.<>\/?]/;
                if(link.match(link_pattern)) {
                    flag = 0;
                    err += 'Link sản phẩm không được chứa ký tự đặc biệt \n';
                }

                // Check product image
                if(image != '') {
                    var imgSize = $('#img')[0].files[0].size;
                    var imgType = $('#img')[0].files[0].type;
                    
                    if(imgSize > 102400) {
                        flag = 0;
                        err += 'Ảnh không được vượt quá 100KB \n';
                    }

                    $img_pattern = /image\/(png|jpg|jpeg)/;
                    if(!imgType.match($img_pattern)) {
                        flag = 0;
                        err += 'Ảnh không hợp lệ \n';
                    }
                }

                if(flag == 1) {
                    // Send data to backend to check
                    var formData = new FormData(this);
                    formData.append('content', CKEDITOR.instances['content'].getData());

                    <?php
                        $link = '';
                        ($url[1] == 'add' ? $link = 'addProcess' : $link = 'editProcess/'.$url[2]);
                    ?>

                    // Send ajax to backend
                    $.ajax({
                        url: 'Product/<? echo $link; ?>',
                        type: 'POST',
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function(rs) {console.log(rs);
                            if(rs == 'Insert Successfully') {
                                window.location.href = 'Product/index';
                            } else if(rs == 'Update Successfully') {
                                window.location.href = '<? echo URL; ?>Product/edit/<? if( isset($url[2]) ){ echo $url[2]; }?>';
                            } else {
                                alert(rs);
                            }
                        }
                    })
                } else {
                    alert(err);
                }
            })
        })
    <?php } ?>

    function deleteProduct(id) {
        $('#tr' + id).remove();

        $.ajax({
            url: 'Product/delete/' + id,
            success: function(rs) {
                if( rs == 'Delete Successfully') {
                    console.log("Xoá sản phẩm thành công");
                }
            }
        });
        return false;
    }
</script>