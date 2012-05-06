<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <base id="base_url" href="<?php echo base_url(); ?>" />
        <link rel="StyleSheet" href="/css/reset.css" type="text/css" />
		<link rel="StyleSheet" href="/css/smoothness/jquery-ui.css" type="text/css" />
        <link rel="StyleSheet" href="/css/header.css" type="text/css" />
        <link rel="StyleSheet" href="/css/menu.css" type="text/css" />
        <link rel="StyleSheet" href="/css/grid_view.css" type="text/css" />
        
        <!-- The general javascript files, such as jQuery or jQuery UI -->
        <script type="text/javascript" src="/js/jquery.min.js"></script>
        <script type="text/javascript" src="/js/jquery-ui.min.js"></script>
        <script type="text/javascript" src="/js/common.js"></script>
        <?php if (file_exists('js/' . $this->uri->segment(1) . '.js')) :?>
            <script type="text/javascript" src="/js/<?php echo $this->uri->segment(1); ?>.js"></script>
        <?php endif; ?> 
    </head>
    <body>
    	<?php
    	if(isset($_SESSION['user']))
        	$this->load->view('template/header', $this->data);
        ?>
    	<div id="wrapper" class="ui-widget ui-helper-clearfix">        
        <?php
        	$this->load->view($view, $this->data);
        ?>
        </div>
        <div id="dialog"></div>
        <?php
    	if(isset($_SESSION['user']))
        	$this->load->view('template/footer', $this->data);
        ?>
    </body>
</html>