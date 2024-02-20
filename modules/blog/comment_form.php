<?php
if (isPost()) {
  $body = getBody();
  $errors = [];

  //Validate name
  if (empty($body['name'])) {
    $errors['name']['required'] = 'Please enter your name';
  } else {
    if (strlen(trim($body['name'])) < 5) {
      $errors['name']['min'] = 'Name must be at least 5 characters';
    }
  }

  //Validate email
  if (empty($body['email'])) {
    $errors['email']['required'] = 'Please enter your email';
  } else {
    if (!isEmail($body['email'])) {
      $errors['email']['invalid'] = 'Please enter a valid email';
    }
  }

  //Validate content
  if (empty($body['content'])) {
    $errors['content']['required'] = 'Please enter your message';
  } else {
    if (strlen(trim($body['content'])) < 10) {
      $errors['content']['min'] = 'Message must be at least 10 characters';
    }
  }

  if (empty($errors)) {
    //Không Có lỗi xảy ra
    $dataInsert = [
      'name' => trim(strip_tags($body['name'])),
      'email' => trim(strip_tags($body['email'])),
      'website' => trim(strip_tags($body['website'])),
      'content' => trim(strip_tags($body['content'])),
      'parent_id' => 0,
      'blog_id' => $id,
      'user_id' => NULL,
      'create_at' => date('Y-m-d H:i:s'),
      'status' => 0
    ];
    $insertStatus = insert('comments', $dataInsert);
    if ($insertStatus) {
      setFlashData('msg', 'Comment added successfully');
      setFlashData('msg_type', 'success');
      redirect('?module=blog&action=detail&id=' . $id . '#comment-form');
    } else {
      setFlashData('msg', 'Comment added failed');
      setFlashData('msg_type', 'danger');
      setFlashData('errors', $errors);
      setFlashData('old', $body);
      redirect('?module=blog&action=detail&id=' . $id . '#comment-form');
    }
  } else {
    //Có lỗi xảy ra
    setFlashData('msg', 'Please check the entered data');
    setFlashData('msg_type', 'danger');
    setFlashData('errors', $errors);
    setFlashData('old', $body);
    redirect('?module=blog&action=detail&id=' . $id . '#comment-form');
  }
}
$msg = getFlashData('msg');
$msgType = getFlashData('msg_type');
$errors = getFlashData('errors');
$old = getFlashData('old');
?>
<div class="comments-form">
  <h2 class="title">Leave a comment</h2>
  <!-- Contact Form -->
  <form class="form" method="post" action="">
    <?php
    getMsg($msg, $msgType);
    ?>
    <div class="row">
      <div class="col-lg-4 col-12">
        <div class="form-group">
          <input type="text" name="name" placeholder="Full Name">
          <?php echo form_error('name', $errors, '<span class="error">', '</span>'); ?>
        </div>
      </div>
      <div class="col-lg-4 col-12">
        <div class="form-group">
          <input type="email" name="email" placeholder="Your Email">
          <?php echo form_error('email', $errors, '<span class="error">', '</span>'); ?>
        </div>
      </div>
      <div class="col-lg-4 col-12">
        <div class="form-group">
          <input type="text" name="website" placeholder="Website">
          <?php echo form_error('website', $errors, '<span class="error">', '</span>'); ?>
        </div>
      </div>
      <div class="col-12">
        <div class="form-group">
          <textarea name="content" rows="5" placeholder="Type Your Message Here"></textarea>
          <?php echo form_error('content', $errors, '<span class="error">', '</span>'); ?>
        </div>
      </div>
      <div class="col-12">
        <div class="form-group button">
          <button type="submit" class="btn primary">Submit Comment</button>
        </div>
      </div>
    </div>
  </form>
  <!--/ End Contact Form -->
</div>