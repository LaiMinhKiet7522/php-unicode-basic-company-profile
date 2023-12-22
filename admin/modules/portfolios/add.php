<?php
if (!defined('_INCODE')) die('Access Deined...');
$data = [
  'pageTitle' => 'Thêm dự án'
];

layout('header', 'admin', $data);
layout('sidebar', 'admin', $data);
layout('breadcrumb', 'admin', $data);

//Lấy userId đăng nhập
$userId = isLogin()['user_id'];

//Xử lý thêm nhóm người dùng
if (isPost()) {

  //Validate form
  $body = getBody(); //Lấy tất cả dữ liệu trong form

  $errors = []; //Mảng lưu trữ các lỗi

  //Validate tên dự án: Bắt buộc nhập

  if (empty(trim($body['name']))) {
    $errors['name']['required'] = 'Tên dự án bắt buộc phải nhập';
  }

  //Validate slug: Bắt buộc nhập
  if (empty(trim($body['slug']))) {
    $errors['slug']['required'] = 'Đường dẫn tĩnh bắt buộc phải nhập';
  }

  //Validate nội dung: Bắt buộc phải nhập
  if (empty(trim($body['content']))) {
    $errors['content']['required'] = 'Nội dung bắt buộc phải nhập';
  }

  //Validate video: Bắt buộc phải nhập
  if (empty(trim($body['video']))) {
    $errors['video']['required'] = 'Link video bắt buộc phải nhập';
  }

  //Validate danh mục: Bắt buộc chọn
  if (empty(trim($body['portfolio_category_id']))) {
    $errors['portfolio_category_id']['required'] = 'Danh mục bắt buộc phải chọn';
  }

  //Validate ảnh đại diện dự án: Bắt buộc phải chọn
  if ($_FILES['thumbnail']['name'] == '') {
    $errors['thumbnail']['required'] = 'Ảnh bắt buộc phải chọn';
  }

  $path = $_FILES['thumbnail']['name'];
  $path_tmp = $_FILES['thumbnail']['tmp_name'];

  $arr = explode(
    '.',
    $path
  );

  $filename = "portfolio_" . uniqid() . '.' . $arr[1];

  //Lưu đường dẫn lên server
  $upload_path = _WEB_HOST_ROOT . '/uploads/' . $filename;

  //Kiểm tra mảng $errors
  if (empty($errors)) {
    //Không có lỗi xảy ra

    if (
      $arr[1] == "jpg" || $arr[1] == "png" || $arr[1] == "jpeg"
    ) {
      move_uploaded_file(
        $path_tmp,
        $_SERVER['DOCUMENT_ROOT'] . '/php-unicode-basic-company-profile/uploads/' . $filename
      );
    } else {
      setFlashData('msg', 'Hình ảnh upload phải thuộc dạng jpg, jpeg, png');
      setFlashData('msg_type', 'danger');
      setFlashData('old', $body);
      redirect('admin?module=portfolios&action=add'); //Load lại dự án dự án
    }

    $save_url = array();
    if ($_FILES['gallery']['name'][0] != '') {
      foreach ($_FILES['gallery']['tmp_name'] as $key => $tmp_name) {
        $file_name_multi = $key . $_FILES['gallery']['name'][$key];
        $file_tmp_multi = $_FILES['gallery']['tmp_name'][$key];

        $array_multi = explode(
          '.',
          $file_name_multi
        );

        $file_name_multi = "portfolio_gallery_" . uniqid() . '.' . $array_multi[1];

        //Lưu đường dẫn lên server
        $upload_path_multi = _WEB_HOST_ROOT . '/uploads/' . $file_name_multi;
        $save_url[] = $upload_path_multi;

        if (
          $array_multi[1] == "jpg" || $array_multi[1] == "png" || $array_multi[1] == "jpeg"
        ) {
          move_uploaded_file(
            $file_tmp_multi,
            $_SERVER['DOCUMENT_ROOT'] . '/php-unicode-basic-company-profile/uploads/' . $file_name_multi
          );
        } else {
          setFlashData('msg', 'Hình ảnh upload phải thuộc dạng jpg, jpeg, png');
          setFlashData('msg_type', 'danger');
          setFlashData('old', $body);
          redirect('admin?module=portfolios&action=add'); //Load lại dự án dự án
        }
      }
    }

    $dataInsert = [
      'name' => trim($body['name']),
      'slug' => trim($body['slug']),
      'content' => trim($body['content']),
      'user_id' => $userId,
      'description' => trim($body['description']),
      'video' => trim($body['video']),
      'portfolio_category_id' => trim($body['portfolio_category_id']),
      'thumbnail' => $upload_path,
      'create_at' => date('Y-m-d H:i:s')
    ];

    $insertStatus = insert('portfolios', $dataInsert);

    $currentId = insertId(); //Lấy id vừa insert 
    foreach ($save_url as $item) {
      $dataImages = [
        'portfolio_id' => $currentId,
        'image' => $item,
        'create_at' => date('Y-m-d H:i:s')
      ];
      insert(
        'portfolio_images',
        $dataImages
      );
    }

    if ($insertStatus) {
      setFlashData('msg', 'Thêm dự án thành công');
      setFlashData('msg_type', 'success');

      redirect('admin?module=portfolios'); //Chuyển hướng qua dự án danh sách

    } else {
      setFlashData('msg', 'Hệ thống đang gặp sự cố! Vui lòng thử lại sau.');
      setFlashData('msg_type', 'danger');

      redirect('admin?module=portfolios&action=add'); //Load lại dự án thêm nhóm người dùng
    }
  } else {

    //Có lỗi xảy ra
    setFlashData('msg', 'Vui lòng kiểm tra dữ liệu nhập vào');
    setFlashData('msg_type', 'danger');
    setFlashData('errors', $errors);
    setFlashData('old', $body);
    redirect('admin?module=portfolios&action=add'); //Load lại dự án dự án
  }
}

$msg = getFlashData('msg');
$msgType = getFlashData('msg_type');
$errors = getFlashData('errors');
$old = getFlashData('old');

//Truy vấn lấy danh sách danh mục
$allCate = getRaw("SELECT * FROM portfolio_categories ORDER BY name");
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <form action="" method="post" enctype="multipart/form-data">
      <?php
      getMsg($msg, $msgType);
      ?>
      <div class="form-group">
        <label for="">Tên dự án</label>
        <input type="text" class="form-control slug" name="name" placeholder="Tên dự án..." value="<?php echo old('name', $old); ?>" />
        <?php echo form_error('name', $errors, '<span class="error">', '</span>'); ?>
      </div>

      <div class="form-group">
        <label for="">Đường dẫn tĩnh</label>
        <input type="text" class="form-control render-slug" name="slug" placeholder="Đường dẫn tĩnh..." value="<?php echo old('slug', $old); ?>" />
        <?php echo form_error('slug', $errors, '<span class="error">', '</span>'); ?>
        <p class="render-link"><b>Link</b>: <span></span></p>
      </div>

      <div class="form-group">
        <label for="">Mô tả</label>
        <textarea name="description" id="file-picker" class="form-control"><?php echo old('description', $old) ?></textarea>
        <?php echo form_error('description', $errors, '<span class="error">', '</span>'); ?>
      </div>

      <div class="form-group">
        <label for="">Nội dung</label>
        <textarea name="content" id="file-picker" class="form-control"><?php echo old('content', $old) ?></textarea>
        <?php echo form_error('content', $errors, '<span class="error">', '</span>'); ?>
      </div>

      <div class="form-group">
        <label for="">Link video</label>
        <input type="url" class="form-control" name="video" placeholder="Link video youtube..." value="<?php echo old('video', $old); ?>" />
        <?php echo form_error('video', $errors, '<span class="error">', '</span>'); ?>
      </div>

      <div class="form-group">
        <label for="">Danh mục</label>
        <select name="portfolio_category_id" class="form-control">
          <option value="0">Chọn danh mục</option>
          <?php
          if (!empty($allCate)) {
            foreach ($allCate as $item) {
          ?>
              <option value="<?php echo $item['id']; ?>" <?php echo (old('portfolio_category_id', $old) == $item['id']) ? 'selected' : false; ?>><?php echo $item['name']; ?></option>
          <?php
            }
          }
          ?>
        </select>
        <?php echo form_error('portfolio_category_id', $errors, '<span class="error">', '</span>'); ?>
      </div>

      <div class="form-group">
        <label for="">Ảnh đại diện</label>
        <input name="thumbnail" class="form-control" type="file" style="width: 20%; padding: 0.25rem 0.75rem !important;" onchange="loadImage(this)">
        <img src="" id="load_image" alt="" style="margin-top: 10px;">
        <?php echo form_error('thumbnail', $errors, '<span class="error">', '</span>'); ?>
      </div>

      <div class="form-group">
        <label for="">Ảnh dự án (có thể chọn nhiều hình)</label>
        <input name="gallery[]" type="file" style="width: 20%; padding: 0.25rem 0.75rem !important;" multiple class="form-control" onchange="loadFile(event)">
        <div class="row" id="cont" style="margin-top: 10px;">

        </div>
      </div>

      <button type="submit" class="btn btn-primary">Thêm mới</button>
      <a href="<?php echo getLinkAdmin('portfolios', 'lists'); ?>" class="btn btn-success">Quay lại</a>
    </form>
  </div>
</section>
<script type="text/javascript">
  function loadImage(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function(e) {
        $('#load_image').attr('src', e.target.result).width(150).height(140);
      };
      reader.readAsDataURL(input.files[0]);
    }
  }
</script>

<script>
  var loadFile = function(event) {
    var imgCont = document.getElementById("cont");
    for (let i = 0; i < event.target.files.length; i++) {
      var divElm = document.createElement('div');
      divElm.style.marginRight = "10px";
      divElm.id = "rowdiv" + i;
      var spanElm = document.createElement('span');
      var image = document.createElement('img');
      image.src = URL.createObjectURL(event.target.files[i]);
      image.id = "output" + i;
      image.width = "150";
      image.height = "140";
      spanElm.appendChild(image);
      var deleteImg = document.createElement('p');
      deleteImg.innerHTML = "x";
      deleteImg.style.cursor = "pointer";
      deleteImg.style.color = "red";
      deleteImg.style.fontWeight = "blod";
      deleteImg.style.fontSize = "20px";
      deleteImg.align = "center";
      deleteImg.onclick = function() {
        this.parentNode.remove()
      };
      divElm.appendChild(spanElm);
      divElm.appendChild(deleteImg);
      imgCont.appendChild(divElm);
    }
  };
</script>
<?php
layout('footer', 'admin', $data);
