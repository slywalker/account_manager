=======================================================================
※このメールは、登録メールアドレスあてに自動的にお送りしています。
※登録内容をご確認のうえ、大切に保管してください。返信の必要はありません。
=======================================================================

当サイトにご登録くださいましてありがとうございます。

あなたのニックネームは <?php echo $user['User']['username']; ?> です。

登録メールアドレスは <?php echo $user['User']['email']; ?> です。

当サイトからの大切なお知らせが正しく届いているかを確認するため、登録メールアドレスの確認を行います。下記URLをクリックしてください。

■登録メールアドレス確認
<?php
echo $html->url(array(Configure::read('Routing.admin') => false, 'action' => 'confirm_register', $user['User']['email_checkcode']), true);
?>

※登録メールアドレス確認を行わないと、サービスのご利用ができません。

===============================
このメールに心あたりがない場合
===============================

どなたかがあなたのメールアドレスを誤って入力されたものと思われます。
お手数をおかけして申し訳ありませんが、このメールを削除してくださいます
ようお願いいたします。
