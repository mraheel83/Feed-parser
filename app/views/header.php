<?php
    $error = $this->getSession('error');
    $success = $this->getSession('success');

    if(isset($error)) {

        $class = 'show';
        $class_type = 'alert alert-danger';
        $message = $error;

    } else if(isset($success)) {

        $class = 'show';
        $class_type = 'alert alert-success';
        $message = $success;

    } else {
        $class = '';
        $class_type = '';
    }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title><?php echo $config['page_title']; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="<?php echo $config['http_lib']; ?>bootstrap/css/bootstrap.min.css" type="text/css" rel="stylesheet"/>
    <link href="<?php echo $config['http_css']; ?>style.css" type="text/css" rel="stylesheet"/>
    <script type="text/javascript">
        var http_base = '<?php echo $config['http_base'];?>'
    </script>
</head>
<body>
<div class="container">