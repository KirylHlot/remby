<?
$recepient = trim($_POST["email"]);
$name = trim($_POST["name"]);
$phone = trim($_POST["phone"]);
$comment = trim($_POST["comment"]);
$page_title = trim($_POST["page_title"]);


$message = "
<div xmlns=\"http://www.w3.org/1999/xhtml\">
  <b>Страница:</b> $page_title
	<br><b>Имя:</b> $name
	<br><b>Телефон:</b> $phone
	<br><b>Сообщение:</b> $comment
</div>";

$pagetitle = 'Сообщение из формы обратной связи';
mail($recepient, $pagetitle, $message, "Content-type: text/html; charset=\"UTF-8\";\n From: $recepient");