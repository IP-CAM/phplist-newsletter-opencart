<?php echo $header; ?>
<style>
.error-field { margin-top: 3px; color: red; font-size: 12px; font-weight: normal; }
label.error-field { padding: 0px 0px 0px 10px; }
input[type=text] { width: 300px; }
label div.label-desc { color: #999; font-size: 11px; letter-spacing: 1px; }
div.preview-twitter-login-btn { margin-top: 10px; }
#form h3 { font-family: "Oswald"; font-weight: 300!important; font-size: 24px!important;
margin: 0px!important; padding: 0px!important; border-bottom: 1px dotted black; }
.alert { padding: 8px 35px 8px 14px; margin-bottom: 20px; text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);
background-color: #FCF8E3; border: 1px solid #FBEED5; -webkit-border-radius: 4px; -moz-border-radius: 4px; 
border-radius: 4px; }
.alert-info { color: #3A87AD; background-color: #D9EDF7; border-color: #BCE8F1; }
.list thead td, .list tbody td, .list tfoot td { text-align: left; padding: 7px; }
input[type=text].input-position { width: 190px; }
.open { display: block; }
.hide { display: none; }
.htabs a.selected {	display: inline-block; }
#content ul {
list-style-type: none;
padding: 0px;
}
#content ul li {
width: 200px;
min-height: 80px;
float: left;
max-height: 60px;
background: #2c3e50;
margin-bottom: 10px;
margin-right: 10px;
}
#content ul li:hover {
background: #2ecc71;
}
#content ul li a {
font-size: 14px;
display: block;
text-decoration: none;
padding: 22px;
text-align: center;
color: #ecf0f1;
}
.wrapper-templates-type {
border: 1px solid #2ecc71;
}
.templates-type {
background: #27ae60;
color: #ecf0f1;
padding: 8px;
font-size: 16px;
}
.templates-type-content {
padding: 0px 10px;
margin-top: 10px;
}
.info {
background: #2980b9;
color: #ecf0f1;
padding: 8px;
}
.info p:first-child {
margin: 0px;
}
.info p {
font-size: 14px;
letter-spacing: 0.3px;
margin: 0px;
margin-top: 4px;
}
.heading {
margin-bottom: 10px;
}
.wrapper-templates-type .buttons {
float: right;
}
a.btn-green, .list a.btn-green, button.btn-green {
text-decoration: none;
color: #ecf0f1;
display: inline-block;
padding: 5px 15px 5px 15px;
font-size: 14px;
background: #2c3e50;
border: none;
cursor: pointer;
}
a.btn-green:hover, .list a.btn-green:hover {
background: #2ecc71;
}
table.form > tbody > tr > td:first-child {
width: 250px;
}
</style>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <div class="">
	<div class="heading">
	<div class="buttons">
	<a onclick="location = '<?php echo $cancel; ?>';" class="btn-green"><?php echo $text_btn_back; ?></a>
	</div>	
	</div>
    <div class="content">
	<div class="wrapper-templates-type">
	<div class="templates-type"><?php echo $heading_title_dashboard; ?></div>
	<div class="templates-type-content">
	<div class="info"><?php echo $description; ?></div>
	<ul>
	<?php
	foreach( $pages as $key=>$value ){
	?>
	<li><a href="<?php echo $value['url']; ?>"><?php echo $value['label']; ?></a></li>
	<?php
	}
	?>	
	</ul>
	</div>
	<div style="clear: both;"></div>
	</div>
    </div>
  </div>
</div>
<?php echo $footer; ?>